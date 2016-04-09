// LED turns red if patient is doing badly
// LED stays green if patient is doing ok
// LED turns yellow if alternative problem
// Buzzer when red
// need variable that is updated that holds the value of the heart rate, 
//  temperature, etc 
// if one of cases is true, then turn on LED red
// if press button clear alarm state (still need to do)

int BP_sys = 100;
int BP_dis = 70;
int heartRate = 120;
int pulseOx = 96; 
float temperature = 96;
// assign variables to pins
int rled = 11;
int yled = 12;
int gled = 13;
int buzz = 10;
int buttonPin = 9;

//variables will change
int alarmState=0;
int buttonState = 0;
int time;

void setup() {
  // initialize digital pins as an output.
  pinMode(rled, OUTPUT);
  pinMode(yled, OUTPUT);
  pinMode(gled, OUTPUT);
  pinMode(buzz, OUTPUT);
  pinMode(buttonPin, OUTPUT);
  Serial.begin(9600);


  // Variables will change :
  digitalWrite(rled,LOW);
  digitalWrite(yled,LOW);
  digitalWrite(gled,LOW);
  digitalWrite(buzz,LOW);
}


void loop() {
  //Turns off alarm if button is pressed
  buttonState = digitalRead(buttonPin);
  if (buttonState == HIGH) {       
    alarmState=0;  
  } 

  //Red LED and buzzer go off every second 
  if (alarmState==1){
    time = millis();
    if (time % 1000 == 0) {
      digitalWrite(rled,HIGH);
      digitalWrite(buzz,HIGH);
    }
  }
  else {
    digitalWrite(rled,LOW);
    digitalWrite(buzz,LOW);
  }

  if(alarmState==0){
    digitalWrite(rled,LOW);
    digitalWrite(buzz,LOW);
  }

  // check for WARNING signs
  if ((temperature==0) || (pulseOx==0) || (heartRate==0) || (BP_sys==0) || (BP_dis==0)) {
    digitalWrite(yled, HIGH);
    alarmState=0;
  }

 if ((temperature>99.2) || (pulseOx<95) || (heartRate<60) || (heartRate>200) || (BP_sys<90) || (BP_sys>119) || (BP_dis<60) || (BP_dis>79)) {

    alarmState=1;
  } 

  else {
    digitalWrite(gled, HIGH);
    alarmState=0;
  }
}

void buzzSilence(){
   if (alarmState==1){ 
    if (myTouch.dataAvailable()){
      delay(50);
      Serial.println("Touch detected in alarm state.");
      alarmState=0;
      Serial.println("alarmState turned off.");
    }
   }







