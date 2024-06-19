#include <Arduino.h>
#include <WiFi.h>
#include <MQTT.h>
#include <Wire.h>
#include <WiFi.h>
#include <WiFiClient.h>
#include <LiquidCrystal_I2C.h>
#include "HX711.h"
#include <NusabotSimpleTimer.h>


#define trigPin 25
#define echoPin 26
#define trigPin2 17
#define echoPin2 16
const int LOADCELL_DOUT_PIN = 18;
const int LOADCELL_SCK_PIN = 19;

HX711 scale;  
LiquidCrystal_I2C lcd(0x27, 16, 2);

WiFiClient wifi;
MQTTClient client;
NusabotSimpleTimer timer;

char ssid[] = "Redmi Note 10 Pro";
char pass[] = "1sampai8";

long duration, distance;
long duration2, distance2;
float tera = 0;
int berat,x,jarak,tinggi,statusnya;
float fix;
int sp = 30;
float kg;
float weight; 
float calibration_factor = 211000; // for me this vlaue works just perfect 211000  
float BMI;

//void sendSensor()
//{
// Blynk.virtualWrite(V0, weight);
// Blynk.virtualWrite(V1, tinggi);
// Blynk.virtualWrite(V2, statusnya);
// delay(1000);
//}

void connect(){
    Serial.print("Menghubungkan ke WiFi");
    while(WiFi.status() != WL_CONNECTED){
      Serial.print(".");
      delay(500);
  }
  Serial.println("Berhasil terhubung ke WiFi!");

  Serial.print("Menghubungkan ke Broker");
  while(!client.connect("idxxxxxxxxxx")){
    Serial.print(".");
    delay(500);
  }
  Serial.println("Berhasil terhubung ke Broker");
  client.subscribe("loadcell/#", 1);
}
void publish(){
  client.publish("loadcell/jarak", String(tinggi));
  client.publish("loadcell/berat", String(weight));
}

void subscribe(String &topic, String &data){
}


void measureweight(){
 scale.set_scale(calibration_factor); //Adjust to this calibration factor
  // Serial.print("Reading: ");
  weight = scale.get_units(5); 
    if(weight<0)
  {
    weight=0.00;
    }
  //Serial.print(scale.get_units(), 2);
 // Serial.print(" lbs"); //Change this to kg and re-adjust the calibration factor if you follow SI units like a sane person
  // Serial.print("Kilogram:");
  // Serial.print( weight); 
  // Serial.print(" Kg");
  // Serial.print(" calibration_factor: ");
  // Serial.print(calibration_factor);
  // Serial.println();
  // Delay before repeating measurement
  delay(100);
}
void usonic(){
  digitalWrite(trigPin, LOW);  // Added this line
  delayMicroseconds(2); // Added this line
  digitalWrite(trigPin, HIGH);
  delayMicroseconds(10); // Added this line
  digitalWrite(trigPin, LOW);
  duration = pulseIn(echoPin, HIGH);
  distance = (duration/2) / 29.1;


  // Serial.print(distance);
  // Serial.println(" cm");
  

  digitalWrite(trigPin2, LOW);  // Added this line
  delayMicroseconds(2); // Added this line
  digitalWrite(trigPin2, HIGH);
  delayMicroseconds(10); // Added this line
  digitalWrite(trigPin2, LOW);
  duration2 = pulseIn(echoPin2, HIGH);
  distance2 = (duration2/2) / 29.1;

  // Serial.print(distance2);
  // Serial.println(" cm");


} 
void result(){
  usonic();
  measureweight();
  tinggi = 55 - distance - distance2;

  // Pastikan tinggi dalam meter untuk perhitungan BMI
  float tinggi_m = tinggi / 100.0;
  BMI = weight / (tinggi_m * tinggi_m);
  
  lcd.setCursor(0,0);
  lcd.print("W/H:");
  lcd.print(weight);
  lcd.print("/");
  lcd.print(tinggi);
  lcd.print(" cm");

  lcd.setCursor(11,1);
  lcd.print(BMI); 
  // Serial.println("BMI");
  // Serial.print(BMI);

  if (BMI < 17){
    statusnya = 1;
    lcd.setCursor(0,1);
    lcd.print(" KURUS   ");
    client.publish("loadcell/result", "KURUS"); 
  } else if (BMI >= 17 && BMI < 23){
    statusnya = 2;
    lcd.setCursor(0,1);
    lcd.print(" NORMAL  ");  
    client.publish("loadcell/result", "NORMAL");
  } else if (BMI >= 23 && BMI < 30){
    statusnya = 3;
    lcd.setCursor(0,1);
    lcd.print(" GEMUK   ");  
    client.publish("loadcell/result", "GEMUK");
  } else if (BMI >= 30){
    statusnya = 4;
    lcd.setCursor(0,1);
    lcd.print(" OBES   ");  
    client.publish("loadcell/result", "OBES");
  }

}
void setup() {

  Serial.begin(9600);
  lcd.begin();
  lcd.clear();
  lcd.noCursor();
  scale.begin(LOADCELL_DOUT_PIN, LOADCELL_SCK_PIN);
//  Blynk.begin(BLYNK_AUTH_TOKEN, ssid, pass);
//  timer.setInterval(1000L, sendSensor);
  pinMode(trigPin, OUTPUT);
  pinMode(echoPin, INPUT);
  pinMode(trigPin2, OUTPUT);
  pinMode(echoPin2, INPUT);

  WiFi.begin(ssid, pass);
  client.begin("broker.emqx.io", wifi);
  client.onMessage(subscribe);
  timer.setInterval(1000, publish);

   lcd.setCursor (0,0);
   lcd.print ("LOADING... ");
   delay(5000);
   lcd.clear();
   
  // Set up serial monitor
  // Serial.println("HX711 calibration sketch");
  // Serial.println("Remove all weight from scale");
  // Serial.println("After readings begin, place known weight on scale");
  // Serial.println("Press + or a to increase calibration factor");
  // Serial.println("Press - or z to decrease calibration factor");
  scale.set_scale();
  scale.tare(); //Reset the scale to 0
  long zero_factor = scale.read_average(); //Get a baseline reading
  Serial.print("Zero factor: "); //This can be used to remove the need to tare the scale. Useful in permanent scale projects.
  Serial.println(zero_factor);

}
void loop() 
{
  client.loop();
  timer.run();
  if(!client.connected()){
    connect();
  } 
  result();
}