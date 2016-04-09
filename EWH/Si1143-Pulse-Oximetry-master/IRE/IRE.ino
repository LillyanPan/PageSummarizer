  /* Si1143 Pulse-ox demo

 by Syed Tahmid Mahbub
 Cornell EWH
 
 Complied with Arduino 1.5.4
 */


#define sleep                100
#define SAMPLES_TO_AVERAGE   5
//#define AMBIENT_LIGHT_SAMPLING // also samples ambient slight (slightly slower)
#define GET_PULSE_READING // prints HB, signal size, PSO2 ratio

#include <Wire.h>
#include "SI1143.h";

int bias1,bias2,bias3;
unsigned int resp;
int blinktime,counter,loopc;
unsigned int Light_Reading;
byte LowB,HighB;
bool selected;

int binOut;     // 1 or 0 depending on state of heartbeat
unsigned long red;        // read value from visible red LED
unsigned long IR1;        // read value from infrared LED1
unsigned long IR2;       // read value from infrared LED2
unsigned long IR_total;     // IR LED reads added together

unsigned int als_vis, als_ir, aux, more;
unsigned int PS1,PS2,PS3;

void setup()
{
  Serial.begin(9600);
  Serial.println(F("==================="));
  Serial.println(F("Pulse Demo"));
  Serial.println(F("Syed Tahmid Mahbub and Manish Patel"));
  
  Wire.begin(); // join i2c bus (address optional for master)
  delay(25);

//  The system must write the value 0x17 to HW_KEY register for proper
//  SI1143 operation
  write_reg(HW_KEY, 0x17);
  
  Serial.print("PART: "); Serial.println(read_reg(PART_ID,1), DEC);
  Serial.print("REV: "); Serial.println(read_reg(REV_ID,1), DEC);
  Serial.print("SEQ: "); Serial.println(read_reg(SEQ_ID,1), DEC);
  
  Serial.println(F("-------------------"));
//------------------------------------
// LED21 register
// LED 1 is the red LED
// LED 2 is the IR LED
//------------------------------------
//  [7, 6, 5, 4]     [3, 2, 1, 0]
//    -- RW --         -- RW --
//  -- LED2_I --     -- LED1_I --
//------------------------------------
//  0000:  No Current
//  0001:  Minimum Current
//  1111:  Maximum Current
//------------------------------------
  write_reg(PS_LED21,0xFF);
  
// LED3 is similar to LED21 register
// except that it has only the lowest
// 4 bits connected, but the modes are the same
// This is an IR LED

  write_reg(PS_LED3, 0x0F);
  param_set(CHLIST,0b00010111);
  
  char parameter = read_reg(PARAM_RD,1);
  //Serial.print("CHLIST = ");
  //Serial.println(parameter,BIN);
  // The above two gives me 0x17 (0b 10111) in the terminal
  delay(1000);
  
  bias();
  
  counter = 0;
  selected = 0;
  blinktime = 75;
  
  write_reg(MEAS_RATE, 0x84); // 4.125ms between device waking up (pg 36)
  write_reg(ALS_RATE, 0x08);  // ALS measurements made every time device wakes up (pg 37)
  write_reg(PS_RATE, 0x08);   // PS measurements made every time device wakes up (pg 38)
  
  param_set(CHLIST, 0x77);    // all measurements on
  
  // increasing PARAM_PS_ADC_GAIN will increase the LED on time and ADC window
  // you will see increase in brightness of visible LED's, ADC output, & noise 
  // datasheet warns not to go beyond 4 because chip or LEDs may be damaged
  param_set(PS_ADC_GAIN, 0x00);

  // You can select which LEDs are energized for each reading.
  // The settings below (in the comments)
  // turn on only the LED that "normally" would be read
  // ie LED1 is pulsed and read first, then LED2 & LED3.

  param_set(PSLED12_SELECT, 0x21); // 21 selects LEDs 2 (IR) & 1 (red) only
  param_set(PSLED3_SELECT, 0x04);  // 4 = LED 3 only

  // Sensors for reading the three LEDs
  // 0x03: Large IR Photodiode
  // 0x02: Visible Photodiode - cannot be read with LEDs on - just for ambient measurement
  // 0x00: Small IR Photodiode
  param_set(PS1_ADCMUX, 0x03);      // PS1 photodiode select
  param_set(PS2_ADCMUX, 0x03);      // PS2 photodiode select
  param_set(PS3_ADCMUX, 0x03);      // PS3 photodiode select
  
  param_set(PS_ADC_COUNTER, 0x70);  // B01110000 is default                                   
  write_reg(COMMAND, PSALS_AUTO_cmd);
  
  Serial.println(F("Initialization Done"));
 
}

void loop(){
  static long bpm, ir;
  float ps02;
  int bpm_ret, ir_ret, ps02_ret;
  
  long count = millis();
  int lc = 0;
  
  bpm = 0; ir = 0; ps02 = 0; lc = 0;
  while (millis() - count < 5000){
    readPulseSensor(&bpm_ret, &ir_ret, &ps02_ret);
    bpm += bpm_ret;
    ir += ir_ret;
    ps02 += ps02_ret;
    lc++;
  }
  
  bpm /= lc;
  ir /= lc;
  ps02 /= lc; ps02 /= 10; // ps02 is in format: 995 which means 99.5 percent, so divide by 10
  
  
  Serial.print("\tBPM: ");     Serial.print(bpm);
  Serial.print("\tIR: ");      Serial.print(ir);
  Serial.print("\tPS02: ");    Serial.print(ps02);       Serial.print(" %");
  Serial.println(" ");
  
  
}

void fetchData(){
  byte* p = (byte*) &resp;
  
  for (byte lc=0; lc<16; lc++){
    p[lc] = (unsigned char) read_reg(RESPONSE+lc,1);
  }
  
}

void fetchLedData(){
  unsigned char q[6];
  q[0] = (unsigned char) read_reg(PS1_DATA0,1);  // low byte
  q[1] = (unsigned char) read_reg(PS1_DATA1,1);  // high byte
  q[2] = (unsigned char) read_reg(PS2_DATA0,1);  // low byte
  q[3] = (unsigned char) read_reg(PS2_DATA1,1);  // high byte
  q[4] = (unsigned char) read_reg(PS3_DATA0,1);  // low byte
  q[5] = (unsigned char) read_reg(PS3_DATA1,1);  // high byte
  
  PS1 = (q[1] << 8) | q[0];
  PS2 = (q[3] << 8) | q[2];
  PS3 = (q[5] << 8) | q[4];
  
}

void readPulseSensor(int* BPM_return, int* IR_return, int* PS02_return) {

    static int foundNewFinger, red_signalSize, red_smoothValley;
    static long red_valley, red_Peak, red_smoothRedPeak, red_smoothRedValley, 
               red_HFoutput, red_smoothPeak; // for PSO2 calc
    static  int IR_valley=0, IR_peak=0, IR_smoothPeak, IR_smoothValley, binOut, lastBinOut, BPM;
    static unsigned long lastTotal, lastMillis, IRtotal, valleyTime = millis(), lastValleyTime = millis(), peakTime = millis(), lastPeakTime=millis(), lastBeat, beat;
    static float IR_baseline, red_baseline, IR_HFoutput, IR_HFoutput2, shiftedOutput, LFoutput, hysterisis;

    unsigned long total=0, start;
    int i=0;
    int IR_signalSize;
    //int BPM;
    float PS02;
    
    red = 0;
    IR1 = 0;
    IR2 = 0;
    total = 0;
    start = millis();

     while (i < SAMPLES_TO_AVERAGE){
       #ifdef AMBIENT_LIGHT_SAMPLING
         fetchData();
       #else
         fetchLedData();
       #endif
     
       red += PS1;
       IR1 += PS2;
       IR2 += PS3;
       i++;
    }

    red = red / i;  // get averages
    IR1 = IR1 / i;
    IR2 = IR2 / i;
    total =  IR1 + IR2 + red;
    IRtotal = IR1 + IR2;  // red excluded
    
   

#ifdef AMBIENT_LIGHT_SAMPLING

    Serial.print(resp, HEX);     // resp
    Serial.print("\t");
    Serial.print(als_vis);       //  ambient visible
    Serial.print("\t");
    Serial.print(als_ir);        //  ambient IR
    Serial.print("\t");

#endif

#ifdef PRINT_LED_VALS

    Serial.print(red);
    Serial.print("\t");
    Serial.print(IR1);
    Serial.print("\t");
    Serial.print(IR2);
    Serial.print("\t");
    Serial.println((long)total);   

#endif

 #ifdef GET_PULSE_READING

    // except this one for Processing heartbeat monitor
    // comment out all the bottom print lines

    if (lastTotal < 20000UL && total > 20000UL) foundNewFinger = 1;  // found new finger!

    lastTotal = total;
     
    // if found a new finger prime filters first 20 times through the loop
    if (++foundNewFinger > 25) foundNewFinger = 25;   // prevent rollover 

    if ( foundNewFinger < 20){
       IR_baseline = total - 200;   // take a guess at the baseline to prime smooth filter
       Serial.println("found new finger");
    }
    
    else if(total > 20000UL) {    // main running function
    
    
        // baseline is the moving average of the signal - the middle of the waveform
        // the idea here is to keep track of a high frequency signal, HFoutput and a 
        // low frequency signal, LFoutput
        // The LF signal is shifted downward slightly downward (heartbeats are negative peaks)
        // The high freq signal has some hysterisis added. 
        // When the HF signal crosses the shifted LF signal (on a downward slope), 
        // we have found a heartbeat.
        IR_baseline = smooth(IRtotal, 0.99, IR_baseline);   // 
        IR_HFoutput = smooth((IRtotal - IR_baseline), 0.2, IR_HFoutput);    // recycling output - filter to slow down response
        
        red_baseline = smooth(red, 0.99, red_baseline); 
        red_HFoutput = smooth((red - red_HFoutput), 0.2, red_HFoutput);
        
        // beat detection is performed only on the IR channel so 
        // fewer red variables are needed
        
        IR_HFoutput2 = IR_HFoutput + hysterisis;     
        LFoutput = smooth((IRtotal - IR_baseline), 0.95, LFoutput);
        // heartbeat signal is inverted - we are looking for negative peaks
        shiftedOutput = LFoutput - (IR_signalSize * .05);

        if (IR_HFoutput  > IR_peak) IR_peak = IR_HFoutput; 
        if (red_HFoutput  > red_Peak) red_Peak = red_HFoutput;
        
        // default reset - only if reset fails to occur for 1800 ms
        if (millis() - lastPeakTime > 1800){  // reset peak detector slower than lowest human HB
            IR_smoothPeak =  smooth((float)IR_peak, 0.6, (float)IR_smoothPeak);  // smooth peaks
            IR_peak = 0;
            
            red_smoothPeak =  smooth((float)red_Peak, 0.6, (float)red_smoothPeak);  // smooth peaks
            red_Peak = 0;
            
            lastPeakTime = millis();
        }

        if (IR_HFoutput  < IR_valley)   IR_valley = IR_HFoutput;
        if (red_HFoutput  < red_valley)   red_valley = red_HFoutput;

        if (millis() - lastValleyTime > 1800){  // insure reset slower than lowest human HB
            IR_smoothValley =  smooth((float)IR_valley, 0.6, (float)IR_smoothValley);  // smooth valleys
            IR_valley = 0;
            lastValleyTime = millis();           
        }

   //     IR_signalSize = IR_smoothPeak - IR_smoothValley;  // this the size of the smoothed HF heartbeat signal
        hysterisis = constrain((IR_signalSize / 15), 35, 120) ;  // you might want to divide by smaller number
                                                                // if you start getting "double bumps"
            
        // Serial.print(" T  ");
        // Serial.print(IR_signalSize); 

        if  (IR_HFoutput2 < shiftedOutput){
            // found a beat - pulses are valleys
            lastBinOut = binOut;
            binOut = 1;
         //   Serial.println("\t1");
            hysterisis = -hysterisis;
            IR_smoothValley =  smooth((float)IR_valley, 0.99, (float)IR_smoothValley);  // smooth valleys
            IR_signalSize = IR_smoothPeak - IR_smoothValley;
            IR_valley = 0x7FFF;
            
            red_smoothValley =  smooth((float)red_valley, 0.99, (float)red_smoothValley);  // smooth valleys
            red_signalSize = red_smoothPeak - red_smoothValley;
            red_valley = 0x7FFF;
            
            lastValleyTime = millis();
             
        } 
        else{
         //   Serial.println("\t0");
            lastBinOut = binOut;
            binOut = 0;
            IR_smoothPeak =  smooth((float)IR_peak, 0.99, (float)IR_smoothPeak);  // smooth peaks
            IR_peak = 0;
            
            red_smoothPeak =  smooth((float)red_Peak, 0.99, (float)red_smoothPeak);  // smooth peaks
            red_Peak = 0;
            lastPeakTime = millis();      
        }

        if (lastBinOut == 1 && binOut == 0){
//            Serial.println(binOut);
        }

        if (lastBinOut == 0 && binOut == 1){
            lastBeat = beat;
            beat = millis();
            BPM = 60000 / (beat - lastBeat);
//            Serial.print(binOut);
//            Serial.print("\t\t BPM: ");
//            Serial.print(BPM);
//            Serial.print("\t\t IR: ");
//            Serial.print(IR_signalSize);
//            Serial.print("\t\t PSO2: ");
            PS02 = (float)red_baseline / (float)(IR_baseline/2);
            PS02 = 1000.0 - PS02*1000.0;
            
            *BPM_return = BPM;
            *IR_return = IR_signalSize;
            *PS02_return = PS02;
            
        }

    }
 #endif
}
