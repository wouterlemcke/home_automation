#include <OneWire.h>
#include <VirtualWire.h>
#include <dht.h>

dht DHT;
OneWire  ds(2);  // on pin 10 (a 4.7K resistor is necessary)
const int ledPin = 13;

String Sensor1Data;
char Sensor1CharMsg[26];

void setup(void) {
  Serial.begin(9600);
  pinMode(ledPin, OUTPUT);
  vw_set_tx_pin(12);
  vw_setup(2000);   // Bits per sec
}

void loop(void) {
  byte i;
  byte present = 0;
  byte type_s;
  byte data[12];
  byte addr[8];
  float celsius;

  //Uitlezen DHT11
  DHT.read11(A0);

  if ( !ds.search(addr)) {
    ds.reset_search();
    delay(250);
    return;
  }

  ds.reset();
  ds.select(addr);
  ds.write(0x44, 1);        // start conversion, with parasite power on at the end

  delay(60000);     // maybe 750ms is enough, maybe not
  //delay(1000);     // maybe 750ms is enough, maybe not

  present = ds.reset();
  ds.select(addr);
  ds.write(0xBE);         // Read Scratchpad

  for ( i = 0; i < 9; i++) {           // we need 9 bytes
    data[i] = ds.read();
  }

  int16_t raw = (data[1] << 8) | data[0];
  if (type_s) {
    raw = raw << 3; // 9 bit resolution default
    if (data[7] == 0x10) {
      raw = (raw & 0xFFF0) + 12 - data[6];
    }
  } else {
    byte cfg = (data[4] & 0x60);
    if (cfg == 0x00) raw = raw & ~7;  // 9 bit resolution, 93.75 ms
    else if (cfg == 0x20) raw = raw & ~3; // 10 bit res, 187.5 ms
    else if (cfg == 0x40) raw = raw & ~1; // 11 bit res, 375 ms
  }
  celsius = (float)raw / 16.0;

  char celsiusString[10];
  char humidityString[10];
  dtostrf(celsius, 6, 2, celsiusString);
  dtostrf(DHT.humidity, 6, 2, humidityString);
  
  //Serial.println(humidityString);

  char myConcatenation[30];
  sprintf(myConcatenation, "%s|%s", celsiusString, humidityString);
  Serial.println(myConcatenation);




  digitalWrite(13, true); // Turn on a light to show transmitting

  vw_send((uint8_t *)myConcatenation, strlen(myConcatenation));
  vw_wait_tx(); // Wait until the whole message is gone
  digitalWrite(13, false); // Turn off a light after transmission

  Serial.println(celsiusString);
}
