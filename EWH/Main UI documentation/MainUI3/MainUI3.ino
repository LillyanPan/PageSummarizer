#include <UTouch.h>
#include <UTouchCD.h>
#include <HW_ARM_defines.h>
#include <HW_ATmega328P.h>
#include <HW_ATmega32U4.h>
#include <HW_AVR.h>
#include <HW_AVR_defines.h>
#include <memorysaver.h>
#include <avr/pgmspace.h>
#include <UTFT.h>
#include <math.h>
#include "temperatureTable.h"
extern uint8_t SmallFont[];
extern uint8_t BigFont[];
extern uint8_t SevenSegNumFont[];
#define dlength 1000 // length of arrays
unsigned long tbefore, tafter;

int temp_pin = A0;
int pulse_pin = A2; // A2 is the analog pin to read off of hardware
int ox_pin = A3;

unsigned int pArray[dlength];
unsigned int peaks[dlength];
unsigned int filtered[dlength];
unsigned int threshold;

//Screen resolution: 319*239
UTFT        myGLCD(ITDB32S, 38,39,40,41);   // Remember to change the model parameter to suit your display module!
UTouch      myTouch(6,5,4,3,2);

//start with this state
int state = 0;
// 0 - title
// 1 - menu
// 2 - keyboard
// 3 - gender select
// 4 - vitals

int age = -1;
int weight = -1;
String gender;
char gend = ' ';
int BP_sys = 100;
int BP_dis = 60;
int heartRate = 120;
float percent_ox = 100.12; 
float t = 120.13;
float pulse = 80.04;
int x,y;
boolean getAge = false;
boolean getWeight = false;
int tdelay = 100;
char stCurrent[20]="";
int stCurrentLen=0;
char stLast[20]="";
boolean atTransition = true;
float temperature = 100;
boolean ageChange = false;
boolean weightChange = false;
boolean genderChange = false;
boolean temperatureWarningFlag = false;
boolean pulseWarningFlag = false;
boolean percentOxWarningFlag = false;

//alarm constants
int rled = A9;
int yled = A10;
int gled = A11;
int buzz = A12;
//int buttonPin = 9;
int alarmState=0;
int buttonState = 0;
int time;
int buttonPushCounter = 0;
int lastButtonState = 0;

void setup(){ 
Serial.begin(9600);

// initialize LCD
  myGLCD.InitLCD();
  myGLCD.clrScr();
  myGLCD.setFont(BigFont); // might have to set this only when using the toch keyboard
  myGLCD.setBackColor(0, 0, 255);
  
// initialize UTouch
  myTouch.InitTouch();
  myTouch.setPrecision(PREC_MEDIUM);
  
// display title page
  titlePage();
  
//initialize alarms
 // initialize digital pins as an output.
  pinMode(rled, OUTPUT);
  pinMode(yled, OUTPUT);
  pinMode(gled, OUTPUT);
  pinMode(buzz, OUTPUT);
 // pinMode(buttonPin, INPUT);
    // Variables will change :
  digitalWrite(rled,LOW);
  digitalWrite(yled,LOW);
  digitalWrite(gled,LOW);
  digitalWrite(buzz,LOW);
}

void titlePage(){
  Serial.println("setup title page here.");
  myGLCD.clrScr();
  myGLCD.fillScr(180,255,255);
  myGLCD.setBackColor(180,255,255);
  myGLCD.setColor(VGA_BLUE);
  myGLCD.print("EWH CORNELL",CENTER,100);
  myGLCD.print("VITAL SIGNS MONITOR",CENTER,120);
  //include image here
//  myGLCD.drawBitmap(0,0,200,46,(unsigned int*) logo);
}
// clean up code by setting coors as variables and using commands like getBackcolor()
void menuPage(){
  Serial.println("setup menu page here.");
  
  myGLCD.clrScr();
  myGLCD.fillScr(180,255,255);
  myGLCD.setBackColor(180,255,255);
  //Age Box
  myGLCD.setColor(VGA_BLUE);
  myGLCD.drawRect(0,0,319,60);
  myGLCD.setFont(BigFont);
  myGLCD.setColor(VGA_BLUE);
  myGLCD.print("Age",CENTER,23);
  
  //Weight Box
  myGLCD.setColor(VGA_BLUE);
  myGLCD.drawRect(0,60,319,120);
  myGLCD.setColor(VGA_BLUE);
  myGLCD.print("Gender",CENTER,84);

  //Gender Box  
  myGLCD.setColor(VGA_BLUE);
  myGLCD.drawRect(0,120,319,180);
  myGLCD.setColor(VGA_BLUE);
  myGLCD.print("Weight",CENTER,144);

 //Done Box
  myGLCD.setColor(VGA_BLUE);
  myGLCD.drawRect(0,180,319,238);
  myGLCD.setColor(VGA_BLUE);
  myGLCD.print("Done",CENTER,204);  
}

void menuPageChange(String cmd){
   if(cmd == "gender"){
     Serial.println("gender change detected.");
     myGLCD.setColor(51,255,102);
     myGLCD.fillRect(0,61,319,119);
      myGLCD.setColor(VGA_BLUE);
      myGLCD.setBackColor(51,255,102);
      if(gend == 'F'){gender = "female";}
      else{gender = "male";}
      String gendStr = "Gender: " + gender;
      myGLCD.print(gendStr,CENTER,84);
   }
   if(cmd == "weight"){
     Serial.println("weight change detected.");
     myGLCD.setColor(51,255,102);
      myGLCD.fillRect(0,121,319,179);
      myGLCD.setColor(VGA_BLUE);
      myGLCD.setBackColor(51,255,102);
      String weightStr = "Weight: ";
      myGLCD.print(weightStr + weight,CENTER,144);
   }
   if(cmd == "age"){
     Serial.println("age change detected.");
    myGLCD.setColor(51,255,102);
    myGLCD.fillRect(0,0,319,59);
    myGLCD.setColor(VGA_BLUE);
    myGLCD.setBackColor(51,255,102);
    String ageStr = "Age: ";
    myGLCD.print(ageStr + age,CENTER,23);
   }
}


int keyboardPage(){
  Serial.println("keyboard input here.");
  for(int i = 0; i<20; i++){
    stCurrent[i]=' ';
    stLast[i]=' ';
  }
  stCurrent[20]='\0';
  stCurrentLen=0;
  stLast[20]='\0';
  myGLCD.clrScr();
  myGLCD.setFont(BigFont);
  myGLCD.setBackColor(180, 255, 255);
  drawButtons();
  int enterFlag=0;
  int num = 0;
while (enterFlag==0)
  {
    if (myTouch.dataAvailable())
    {
      myTouch.read();
      x=myTouch.getX();
      y=myTouch.getY();
      
      if ((y>=10) && (y<=60))  // Upper row
      {
        if ((x>=10) && (x<=60))  // Button: 1
        {
          waitForIt(10, 10, 60, 60);
          updateStr('1');
        }
        if ((x>=70) && (x<=120))  // Button: 2
        {
          waitForIt(70, 10, 120, 60);
          updateStr('2');
        }
        if ((x>=130) && (x<=180))  // Button: 3
        {
          waitForIt(130, 10, 180, 60);
          updateStr('3');
        }
        if ((x>=190) && (x<=240))  // Button: 4
        {
          waitForIt(190, 10, 240, 60);
          updateStr('4');
        }
        if ((x>=250) && (x<=300))  // Button: 5
        {
          waitForIt(250, 10, 300, 60);
          updateStr('5');
        }
      }

      if ((y>=70) && (y<=120))  // Center row
      {
        if ((x>=10) && (x<=60))  // Button: 6
        {
          waitForIt(10, 70, 60, 120);
          updateStr('6');
        }
        if ((x>=70) && (x<=120))  // Button: 7
        {
          waitForIt(70, 70, 120, 120);
          updateStr('7');
        }
        if ((x>=130) && (x<=180))  // Button: 8
        {
          waitForIt(130, 70, 180, 120);
          updateStr('8');
        }
        if ((x>=190) && (x<=240))  // Button: 9
        {
          waitForIt(190, 70, 240, 120);
          updateStr('9');
        }
        if ((x>=250) && (x<=300))  // Button: 0
        {
          waitForIt(250, 70, 300, 120);
          updateStr('0');
        }
      }

      if ((y>=130) && (y<=180))  // Upper row
      {
        if ((x>=10) && (x<=150))  // Button: Clear
        {
          waitForIt(10, 130, 150, 180);
          stCurrent[0]='\0';
          stCurrentLen=0;
          myGLCD.setColor(0, 0, 0);
          myGLCD.fillRect(0, 224, 319, 239);
        }
        if ((x>=160) && (x<=300))  // Button: Enter
        {
          waitForIt(160, 130, 300, 180);
          
          if (stCurrentLen>0)
          {
            enterFlag=1;

          }
          else
          {
            myGLCD.setColor(255, 0, 0);
            myGLCD.print("NO VALUE ENTERED", CENTER, 192);
            delay(500);
            myGLCD.print("                ", CENTER, 192);
            delay(500);
            myGLCD.print("NO VALUE ENTERED", CENTER, 192);
            delay(500);
            myGLCD.print("                ", CENTER, 192);
            myGLCD.setColor(0, 255, 0);
              for(int i = 0; i<20; i++){
                stCurrent[i]=' ';
                stLast[i]=' ';
              }
              stCurrent[20]='\0';
              stCurrentLen=0;
              stLast[20]='\0';
          }
        }
      
      for(int i = 0;i<stCurrentLen;i++){
          num = num+(pow(10,(stCurrentLen-i-1))*(stCurrent[i]-'0'));
      }    
       if(num > 99){
        num = (int)ceil(num) + 1;
      }else{    
        num = (int)ceil(num);
      }
      }
    }
  }      
  return num;
}

void genderPage(){
  Serial.println("Select Gender here.");
  myGLCD.clrScr();
  myGLCD.fillScr(180,255,255);
  myGLCD.setBackColor(180,255,255);
  myGLCD.setColor(VGA_BLUE);
  myGLCD.drawRect(0,0,160,239);
  myGLCD.fillCircle(80,60,30);
  myGLCD.fillRect(50,93,110,180);
  myGLCD.fillRect(35,93,45,145);
  myGLCD.fillRect(115,93,125,145);
  
  myGLCD.setColor(VGA_RED);
  myGLCD.fillCircle(240,60,30);
  myGLCD.drawRect(161,0,319,239);
  myGLCD.fillRect(210,93,270,180);
  myGLCD.fillRect(195,93,205,145);
  myGLCD.fillRect(275,93,285,145);
}

void displayVitals(){
  Serial.println("Dispaly vitals here.");
}

// main loop
void loop(){
  //delay(tdelay);
  Serial.print("State = ");
  Serial.println(state);
  // STATE 0: Title Page
  if(state == 0){
    if (myTouch.dataAvailable())
      {
        delay(50);
        Serial.println("Touch detected in state 0.");
        // print for debugging 
        myTouch.read();
        int x = myTouch.getX();
        int y = myTouch.getY();
        Serial.print("x = ");
        Serial.println(x);
        Serial.print("y = ");
        Serial.println(y);
        
        // change state 
        state = 1;
        atTransition = true;
        delay(tdelay);
        Serial.println("Changed state to 1.");
      }
      else{
        Serial.println("No data.");
      }
  }
  
  // STATE 1: Menu Page
  if(state == 1){
    if(atTransition){
      menuPage();
        if(ageChange){
          menuPageChange("age");
        }
        if(genderChange){
          menuPageChange("gender");
        }
        if(weightChange){
          menuPageChange("weight");
        }
      atTransition = false;
    }
    
    if (myTouch.dataAvailable()){
      delay(tdelay);
      Serial.println("touch detected state 1");
      myTouch.read();
      y=myTouch.getY();
      
        int x = myTouch.getX();
        int y = myTouch.getY();
        Serial.print("x = ");
        Serial.println(x);
        Serial.print("y = ");
        Serial.println(y);
      
      // Go to age selection 
      if((y>=0) && (y<=60)){
        state = 2;
        getAge = true;
        getWeight = false;
        atTransition = true;
        Serial.println("Age tapped");
      }
      
      // Go to gender selection
      if((y>60) && (y<=120)){
        state = 3;
        atTransition = true;
        Serial.println("gender tapped");
      }
      
      // Go to weight selection 
      if((y>120) && (y<=180)){
        state = 2;
        getAge = false;
        getWeight = true;
        atTransition = true;
        Serial.println("weight tapped");
      }
      // Go to vital signs 
      if(y>180){
        Serial.println("Done tapped");
        // when all params are filled
        if(gend != ' ' && age != -1 && weight != -1){
          state = 4;
          atTransition = true;
        }else{
          Serial.println("Not all parameters have been filled");
        }
      }
    }else{Serial.println("No data.");}
  }
  
    // STATE 2: keyboard Page
  if(state == 2){
    int num;
    if(atTransition){
      atTransition = false;
    }
      num = keyboardPage();
      Serial.println(num);
      if(getAge){
        age = num;
        atTransition = true;
        state = 1;
        ageChange= true;
      }
      if(getWeight){
        weight = num;
        atTransition = true;
        state = 1;
        weightChange = true;
      }
  }
  
    // STATE 3: gender select Page
  if(state == 3){
    if(atTransition){
      atTransition = false;
      genderPage();
    }
    if (myTouch.dataAvailable()){
      delay(tdelay);
      Serial.println("touch detected in gender selection");
      myTouch.read();
      y=myTouch.getY();
      
        int x = myTouch.getX();
        int y = myTouch.getY();
        Serial.print("x = ");
        Serial.println(x);
        Serial.print("y = ");
        Serial.println(y);
        
        if(x<160){
        atTransition = true;
        state = 1;
        gend = 'M';
        genderChange = true;
        }
        else{
          atTransition = true;
          state = 1;
          gend = 'F';
          genderChange = true;
        }
    }
    else
    {
     Serial.println("Select gender.");
    }
  }
  
    // STATE 4: Vitals
  if(state == 4){
    if(atTransition){
      myGLCD.setColor(0,220,0);
      myGLCD.fillRect(0,0,319,239);  
      myGLCD.setColor(255,255,255);
      myGLCD.setColor(0,0,0);
      myGLCD.drawLine(160,0,160,239);
      myGLCD.drawLine(0,120,319,120);
      atTransition = false;
    }
 
      Serial.print("BP:");
      //Blood Pressure Stuff
      myGLCD.setColor(255,255,255);
      myGLCD.setBackColor(0,220,0);
      myGLCD.setFont(BigFont);
      myGLCD.print("Age:",0,0);
      myGLCD.print("Gender:",0,40);
      myGLCD.print("Weight:",0,80);
      myGLCD.setFont(SevenSegNumFont);
      myGLCD.setFont(SmallFont);
      myGLCD.setColor(255,255,255);
      myGLCD.printNumI(age,80,2);
      myGLCD.print(gender,110,42);
      myGLCD.printNumI(weight,130,82);
      
    
// Pulse Setting
//      pulse = getPulse(pulse_pin);
      pulse = 120;
    if(pulse < 40 || pulse > 140){
       pulseWarningFlag = true;
      }else{
      	pulseWarningFlag = false;
      }
      // set color by checking for warning sign
    if(!pulseWarningFlag){
        myGLCD.setColor(0,220,0);
        myGLCD.setBackColor(0,220,0);
        myGLCD.fillRect(161,0,319,119);
        myGLCD.setColor(255,255,255);
    }else{
        myGLCD.setColor(230,0,0);
        myGLCD.setBackColor(230,0,0);
        myGLCD.fillRect(161,0,319,119);
        myGLCD.setColor(255,255,255);
    }
      myGLCD.setFont(BigFont);
      myGLCD.setColor(255,255,255);
      myGLCD.print("Pulse:",161,0);
      myGLCD.setFont(SmallFont);
      myGLCD.print("bpm",285,104);

      Serial.println("Getting pulse");
      myGLCD.setFont(BigFont);
      myGLCD.printNumF(pulse,2,210,50);  
      myGLCD.setBackColor(0,220,0);
    
      
// Temperature Setting
      temperature = getTemperature(temp_pin);
    if(temperature>99.2){
       temperatureWarningFlag = true;
      }else{
      	temperatureWarningFlag = false;
      }
    // set color by checking for warning sign
    if(!temperatureWarningFlag){
        myGLCD.setColor(0,220,0);
        myGLCD.setBackColor(0,220,0);
        myGLCD.fillRect(0,121,159,239);
        myGLCD.setColor(255,255,255);
    }else{
        myGLCD.setColor(230,0,0);
        myGLCD.setBackColor(230,0,0);
        myGLCD.fillRect(0,121,159,239);
        myGLCD.setColor(255,255,255);
    }
      myGLCD.setFont(BigFont);
      myGLCD.setColor(255,255,255);
      myGLCD.print("Temp:",0,122);
      myGLCD.setFont(SmallFont);
      myGLCD.print("deg F",120,224);

      Serial.println("Getting temperature");
      myGLCD.setFont(BigFont);
      myGLCD.printNumF(temperature,2,35,170);  
      myGLCD.setBackColor(0,220,0);
      
      
// Percent Ox Setting
      percent_ox = getPercentOx(ox_pin);
    if(percent_ox<95){
       percentOxWarningFlag = true;
      }else{
      	percentOxWarningFlag = false;
      }
    // set color by checking for warning sign
    if(!percentOxWarningFlag){
        myGLCD.setColor(0,220,0);
        myGLCD.setBackColor(0,220,0);
        myGLCD.fillRect(161,121,319,239);
        myGLCD.setColor(255,255,255);
    }else{
        myGLCD.setColor(230,0,0);
        myGLCD.setBackColor(230,0,0);
        myGLCD.fillRect(161,121,319,239);
        myGLCD.setColor(255,255,255);
    }
      myGLCD.setFont(BigFont);
      myGLCD.setColor(255,255,255);
      myGLCD.print("Oxygen%:",161,121);
      myGLCD.setFont(SmallFont);
      myGLCD.print("%Ox",285,226);

      myGLCD.setFont(BigFont);
      myGLCD.printNumF(percent_ox,2,210,170); 
      myGLCD.setBackColor(0,220,0);


//Alarm system
      if(temperature>99.2 || percent_ox<95){
        alarmState=1;
      }
      if (temperature==-1 || percent_ox==-1){
        alarmState=2;
      }
      else{
        alarmState=0;
      }
      if (alarmState==1){  
        Serial.println("alarmState = 1");
       time = millis();
    //if (time % 1000 == 0) {
      digitalWrite(rled,HIGH);
      Serial.println("Here");
      Serial.println(analogRead(A9));
      digitalWrite(buzz,HIGH);
     Serial.println("red light on");
     if (myTouch.dataAvailable()){
       alarmState=0;
       Serial.println("buzzer turning off");
       digitalWrite(rled,LOW);
       digitalWrite(buzz,LOW);
       digitalWrite(gled,HIGH);
       }
     if (alarmState==2){
       digitalWrite(gled,LOW);
       digitalWrite(yled,HIGH);
     }
    
  }
  delay(2000);
  }
}

void drawButtons()
{
// Draw the upper row of buttons
  for (x=0; x<5; x++)
  {
    myGLCD.setColor(180, 255, 255);
    myGLCD.fillRoundRect (10+(x*60), 10, 60+(x*60), 60);
    myGLCD.setColor(0, 0, 255);
    myGLCD.drawRoundRect (10+(x*60), 10, 60+(x*60), 60);
    myGLCD.printNumI(x+1, 27+(x*60), 27);
  }
// Draw the center row of buttons
  for (x=0; x<5; x++)
  {
    myGLCD.setColor(180, 255, 255);
    myGLCD.fillRoundRect (10+(x*60), 70, 60+(x*60), 120);
    myGLCD.setColor(0, 0, 255);
    myGLCD.drawRoundRect (10+(x*60), 70, 60+(x*60), 120);
    if (x<4)
      myGLCD.printNumI(x+6, 27+(x*60), 87);
  }
  myGLCD.print("0", 267, 87);
// Draw the lower row of buttons
  myGLCD.setColor(180, 255, 255);
  myGLCD.fillRoundRect (10, 130, 150, 180);
  myGLCD.setColor(0, 0, 255);
  myGLCD.drawRoundRect (10, 130, 150, 180);
  myGLCD.print("Clear", 40, 147);
  myGLCD.setColor(180, 255, 255);
  myGLCD.fillRoundRect (160, 130, 300, 180);
  myGLCD.setColor(0, 0, 255);
  myGLCD.drawRoundRect (160, 130, 300, 180);
  myGLCD.print("Enter", 190, 147);
  myGLCD.setBackColor (0, 0, 255);
}

void updateStr(int val)
{
  if (stCurrentLen<20)
  {
    stCurrent[stCurrentLen]=val;
    stCurrent[stCurrentLen+1]='\0';
    stCurrentLen++;
    myGLCD.setColor(0, 255, 0);
    myGLCD.print(stCurrent, LEFT, 224);
  }
  else
  {
    myGLCD.setColor(255, 0, 0);
    myGLCD.print("BUFFER FULL!", CENTER, 192);
    delay(500);
    myGLCD.print("            ", CENTER, 192);
    delay(500);
    myGLCD.print("BUFFER FULL!", CENTER, 192);
    delay(500);
    myGLCD.print("            ", CENTER, 192);
    myGLCD.setColor(0, 255, 0);
  }
}

// Draw a red frame while a button is touched
void waitForIt(int x1, int y1, int x2, int y2)
{
  myGLCD.setColor(255, 0, 0);
  myGLCD.drawRoundRect (x1, y1, x2, y2);
  while (myTouch.dataAvailable())
    myTouch.read();
  myGLCD.setColor(255, 255, 255);
  myGLCD.drawRoundRect (x1, y1, x2, y2);
}

float getTemperature(int temp_pin) {
   // Take in ADC reading
   analogReference(EXTERNAL);
   unsigned int voltage_ADC = analogRead(temp_pin);
   
   // Use a simple binary search to find the index of the value of the
   // ADC reading inside the lookup table. The temperature (in Celcius)
   // corresponds to 20 + .05*(index of ADC). In terms of this search,
   // the index of the ADC reading will correspond to mid once the
   // search is complete.
   int first = 0, last = sizeof(readings)/2-1, mid = (first + last)/2;
   while(first<last) {
      if(voltage_ADC < readings[mid]) {
        first = mid+1;
        mid = (first+last)/2;
      }
      else if(voltage_ADC > readings[mid]) {
        last = mid-1;
        mid = (first+last)/2;
      }
      else {
        break;
      }
  }
  // Convert temperature to Fahrenheit
  float temp = (20.0+.05*(float)mid)*1.8+32.0;
  return temp;
}

// Helper function for getPulse
unsigned int xPeaks(){
//  firstMAF5();
//  secondMAF5();
  // determine threshold value. Find the min and max value in a 2 sec
  // interval and subtract them. 50% of this difference is the
  // threshold value.
  unsigned int maximum = 0;
  unsigned int minimum = 60000;
  for(int x=400; x<600; x++) {
    if(pArray[x] > maximum) {
      maximum = pArray[x];
    }
    if(pArray[x] < minimum) {
      minimum = pArray[x];
    }
  }
  threshold = (int)(.50*(maximum-minimum));
  
  // Store the x values of all of the major peaks in the signal
  // A major peak is defined as when the difference between a 
  // successive min and max value is greater than the threshold
  int index=0;
  int x=0;
  int minIndex=0;
  while(x<dlength-2) {
      // climb down the hill
      while(x<dlength-1 && pArray[x+1]<pArray[x]) {
          x++;
      }
      // store the x value of the min
      minIndex=x;
      // climb up the hill
      while(x<dlength-1 && pArray[x+1]>pArray[x]) {
          x++;
      }
      // check to see if the difference between the
      // min and max value is great than threshold
      if(pArray[x]-pArray[minIndex] >= threshold) {
          peaks[index]= x;
          index++;
      }
      
      x++;
  }
  //remove last value added
  peaks[index-1]=0;
  index=index-1;
  return index-1; // return index-1 because it won't work otherwise
}


float getPulse(int pulse_pin){
  unsigned int adr;
  for (int i = 0; i < dlength; i++){
    adr = analogRead(pulse_pin);
    pArray[i] = adr*10;
    delay(10);
  }
  
  int index = xPeaks();
  volatile double sum = 0.0;
  volatile double pulse = 0.0;
  for(int x=0; x<index; x++) {
    sum += (peaks[x+1]-peaks[x]);
  }
  pulse = 60.0/(sum/(index)*.010);
  return pulse;
    return 100.4;
}

float getPercentOx(int ox_pin){
  return 100;
}

