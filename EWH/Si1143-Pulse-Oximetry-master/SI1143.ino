#include "SI1143.h"

unsigned int read_light(){  // Read light sensor
  write_reg(COMMAND, ALS_FORCE_cmd);
  delay(5);
  byte LowB = read_reg(ALS_VIS_DATA0,1);
  byte HighB = read_reg(ALS_VIS_DATA1,1);
  unsigned int result = (HighB << 8) | LowB;
  return result;
}

void param_set(byte address, byte val)  // Set Parameter
{
  write_reg(PARAM_WR, val);
  write_reg(COMMAND, 0xA0|address);
}

char read_reg(unsigned char address, int num_data) // Read a Register
{
  // num_data: number of bytes of data to request
  unsigned char data;

  Wire.beginTransmission(IR_ADDRESS);
  Wire.write(address);
  Wire.endTransmission();

  Wire.requestFrom(IR_ADDRESS, num_data);
  
  while(Wire.available() < num_data);
  
  return Wire.read();
}

void write_reg(byte address, byte val) {  // Write a register
  Wire.beginTransmission(IR_ADDRESS); 
  Wire.write(address);      
  Wire.write(val);       
  Wire.endTransmission();     
}

void bias(void){  // Bias during start up
  
  for (int i=0; i<20; i++){
    write_reg(COMMAND,PS_FORCE_cmd);
    delay(50);
  
    byte LowB = read_reg(PS1_DATA0,1);
    byte HighB = read_reg(PS1_DATA1,1);
  
    bias1 += ((HighB << 8) | LowB) / 20;
  
    LowB = read_reg(PS2_DATA0,1);
    HighB = read_reg(PS2_DATA1,1);
  
    bias2 += ((HighB << 8) | LowB) / 20;
  
    LowB = read_reg(PS3_DATA0,1);
    HighB = read_reg(PS3_DATA1,1);
  
    bias3 += ((HighB << 8) | LowB) / 20;
 }
 
}

int smooth(int data, float filterVal, float smoothedVal){
  if (filterVal > 1){      // check to make sure param's are within range
    filterVal = .99;
  }
  else if (filterVal <= 0){
    filterVal = 0;
  }

  smoothedVal = (data * (1 - filterVal)) + (smoothedVal  *  filterVal);

  return (int)smoothedVal;
}
