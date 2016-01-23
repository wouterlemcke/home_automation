#include <VirtualWire.h>


int ledPin = 13;

char Sensor1CharMsg[26];

void setup() {
  Serial.begin(9600);
  pinMode(ledPin, OUTPUT);
  vw_set_rx_pin(12);
  vw_set_ptt_inverted(true); //geen idee waar dit voor is...
  vw_setup(2000); //aantal bits per seconde
  vw_rx_start();

} 

void loop() {
  uint8_t buf[VW_MAX_MESSAGE_LEN];
  uint8_t buflen = VW_MAX_MESSAGE_LEN;

  // Non-blocking
  if (vw_get_message(buf, &buflen))
  {
    int i;
    digitalWrite(13, true);

    // Message with a good checksum received, dump it.
    for (i = 0; i < buflen; i++)
    {
      Sensor1CharMsg[i] = char(buf[i]);
    }

    // DEBUG
    Serial.println(Sensor1CharMsg);
    
    //clear array, wait for next message
    for (i = 0; i < buflen; i++)
    {
      Sensor1CharMsg[i] = 0;
    }

    // Turn off light to and await next message
    digitalWrite(13, false);
  }
}
