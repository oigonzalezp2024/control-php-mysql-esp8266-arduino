// Pines definidos según tu prueba exitosa
int leds[] = {2, 3, 4, 5};

void setup() {
  for (int i = 0; i < 4; i++) {
    pinMode(leds[i], OUTPUT);
  }
  Serial.begin(9600); // Velocidad igual a la del ESP8266
  
  // Pequeña señal de que el ATMega inició correctamente
  digitalWrite(2, HIGH); delay(200); digitalWrite(2, LOW);
}

void loop() {
  if (Serial.available() > 0) {
    // Leemos hasta encontrar el salto de línea enviado por el ESP
    String bin = Serial.readStringUntil('\n');
    bin.trim(); // Limpia caracteres invisibles

    if (bin.length() >= 4) {
      // Mapeo inverso: bin[3] es el último bit enviado (Pin 2)
      digitalWrite(leds[0], (bin[3] == '1') ? HIGH : LOW); // Pin 2
      digitalWrite(leds[1], (bin[2] == '1') ? HIGH : LOW); // Pin 3
      digitalWrite(leds[2], (bin[1] == '1') ? HIGH : LOW); // Pin 4
      digitalWrite(leds[3], (bin[0] == '1') ? HIGH : LOW); // Pin 5
    }
  }
}