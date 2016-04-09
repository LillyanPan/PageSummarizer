// Define signal parameters
int Sampling_Time = 5;
int Num_Samples = 600;
int Peak_Threshold_Factor = 80;
int Minimum_Range = 500;
int Minimum_Peak_Separation = 50;  // 50*5=250 ms
int Moving_Average_Num = 10;
int Index1, Index2, Index3, i, j, k, ZeroFlag;
float Pulse_Rate, Temp1, Peak1, Peak2, Peak3, PR1, PR2, ADC_Range;
float Amplification_Factor,Peak_Magnitude, Peak_Threshold, Minima, Range, temp, Sum_Points, Num_Points;
//not integer constant float ADC_Value[Num_Samples];
float ADC_Value[600];
//int[] ADC_Index = new int[Num_Samples];
int ADC_Index[600];

// Define display
float plotX1, plotY1;
float plotX2, plotY2;
float labelX, labelY;
int rowCount;
int columnCount;
int currentColumn = 0;
 int count = 0;
int yearMin, yearMax;
//empty array intiliazation?
int years[0];

int xInterval = 10;
int yInterval = 200;

void setup() {
  Serial.begin(9600);
}

void loop(){
  delay(2000);
  Serial.println("Start reading values");
  ReadSamples();
  RemoveDC();
  if(ADC_Range < 50) {
    ZeroFlag = 1;
    ZeroData();
  } else {
    ZeroFlag=0;
  }
  
  ScaleData();
  FilterData();
  ComputeHeartRate();
  
  Serial.print("Pulse Rate = ");
  Serial.println(Pulse_Rate);
}

// don't have to look into serial because sensor is connected to analog port
void ReadSamples(){
  count = 0;
  while (count < Num_Samples){
     int sensorValue = analogRead(A0);
     ADC_Value[count] = sensorValue;
     ADC_Index[count] = count;
     count = count + 1;
//     Serial.println(sensorValue);
     delay(5);
  }
}

void RemoveDC(){
  Find_Minima(0);
  Find_Peak(0);
  ADC_Range = Peak_Magnitude-Minima;
  Serial.print("Peak Magnitude2:");
  Serial.println(Peak_Magnitude);
  Serial.print("Minima:");
  Serial.println(Minima);
  Serial.print("Range of ADC_Samples=");
  Serial.println(Range);
  
  // Subtract DC (minima) 
  for (int i = 0; i < Num_Samples; i++){
     ADC_Value[i] = ADC_Value[i] - Minima;
  }    
  Minima = 0;  // New Minima is zero
}  

void Find_Minima(int Num){
  Minima = 1024;
  for (int m = Num; m < Num_Samples-Num; m++){
      if(Minima > ADC_Value[m]){
        Minima = ADC_Value[m];
      }
  }
}

void Find_Peak(int Num){
  Peak_Magnitude = 0;
  for (int m = Num; m < Num_Samples-Num; m++){
      if(Peak_Magnitude < ADC_Value[m]){
        Peak_Magnitude = ADC_Value[m];
     }
  }
}

void ZeroData(){
  for (int i = 0; i < Num_Samples; i++){
     ADC_Value[i] = 0;
     
  }
}

void ScaleData(){
  // Find peak value
  Find_Peak(0);
  Range = Peak_Magnitude - Minima;
  // Sclae from 1 to 1023 
  for (int i = 0; i < Num_Samples; i++){
     ADC_Value[i] = 1 + ((ADC_Value[i]-Minima)*1022)/Range; 
  }
//  
//  delay(2000);
//  for (int i = 0; i < Num_Samples; i++){
//     Serial.println(ADC_Value[i]);
//  }
}

void FilterData(){
  Num_Points = 2*Moving_Average_Num+1;
  for (i = Moving_Average_Num; i < Num_Samples-Moving_Average_Num; i++){
    Sum_Points = 0;
    for(k =0; k < Num_Points; k++){   
      Sum_Points = Sum_Points + ADC_Value[i-Moving_Average_Num+k]; 
    }    
  ADC_Value[i] = Sum_Points/Num_Points; 
  } 
  Find_Peak(Moving_Average_Num);
  Find_Minima(Moving_Average_Num);  
  Serial.print("Peak Magnitude2= ");Serial.println(Peak_Magnitude);
  Serial.print("Minima = ");Serial.println(Minima);
}

void ComputeHeartRate(){
  // Detect Peak magnitude and minima
  Find_Peak(Moving_Average_Num);
  Find_Minima(Moving_Average_Num);
  Serial.println("Peak Magnitude3= ");Serial.println(Peak_Magnitude); 
  Serial.println(", Minima = ");Serial.println(Minima); 
  Range = Peak_Magnitude - Minima;
  Peak_Threshold = Peak_Magnitude*Peak_Threshold_Factor;
  Peak_Threshold = Peak_Threshold/100;
  // Now detect three successive peaks 
  Peak1 = 0;
  Peak2 = 0;
  Peak3 = 0;
  Index1 = 0;
  Index2 = 0;
  Index3 = 0;
  // Find first peak
  for (j = Moving_Average_Num; j < Num_Samples-Moving_Average_Num; j++){
      if(ADC_Value[j] >= ADC_Value[j-1] && ADC_Value[j] > ADC_Value[j+1] && ADC_Value[j] > Peak_Threshold && Peak1 == 0){
           Peak1 = ADC_Value[j];
           Index1 = j; 
      }
      // Search for second peak which is at least 10 sample time far
      if(Peak1 > 0 && j > (Index1+Minimum_Peak_Separation) && Peak2 == 0){
         if(ADC_Value[j] >= ADC_Value[j-1] && ADC_Value[j] > ADC_Value[j+1] && ADC_Value[j] > Peak_Threshold){
            Peak2 = ADC_Value[j];
            Index2 = j; 
         } 
      } // Peak1 > 0
      
      // Search for the third peak which is at least 10 sample time far
      if(Peak2 > 0 && j > (Index2+Minimum_Peak_Separation) && Peak3 == 0){
         if(ADC_Value[j] >= ADC_Value[j-1] && ADC_Value[j] > ADC_Value[j+1] && ADC_Value[j] > Peak_Threshold){
            Peak3 = ADC_Value[j];
            Index3 = j; 
         } 
      } // Peak2 > 0
      
       PR1 = (Index2-Index1)*Sampling_Time; // In milliseconds
       PR2 = (Index3-Index2)*Sampling_Time;
//       println("PR1 = "+PR1+", PR2 = "+PR2);
     if(PR1 > 0 && abs(PR1-PR2) < 100){
        Pulse_Rate = (PR1+PR2)/2;
        Pulse_Rate = 60000/Pulse_Rate; // In BPM
    //    println("Index2= "+ Index2 + ", Index1 = "+ Index1+", PulseRate= "+Pulse_Rate); 
    //    println("Peak Magnitude= "+ Peak_Magnitude + ", Minima = "+ Minima); 
     }

  }
}  
