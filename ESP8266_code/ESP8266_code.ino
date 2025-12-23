#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>
#include <ArduinoJson.h>

const char* ssid = "ECOBULL";
const char* password = "71707754";

// Datos quemados en memoria Flash
const char MODELO_JSON[] PROGMEM = R"=====(
{
    "data": [
        ["0000", "en_espera"],
        ["0001", "seleccionado"],
        ["0010", "sucio"],
        ["0011", "limpio"],
        ["0100", "aglutinado"],
        ["0101", "peletizado"],
        ["0110", "molido"],
        ["1111", "error"]
    ]
}
)=====";

ESP8266WebServer server(80);

String buscarEnModelo(String estadoBuscado) {
    StaticJsonDocument<1024> doc;
    // Uso de FlashStringHelper para evitar Exception(3)
    deserializeJson(doc, (const __FlashStringHelper*)MODELO_JSON);
    JsonArray data = doc["data"];
    for (JsonArray fila : data) {
        if (fila[1].as<String>() == estadoBuscado) return fila[0].as<String>();
    }
    return "1111"; 
}

void handleConsultar() {
    String estado = "";
    if (server.hasArg("plain")) {
        StaticJsonDocument<256> inputDoc;
        deserializeJson(inputDoc, server.arg("plain"));
        estado = inputDoc["data"][0][0].as<String>();
    } else if (server.hasArg("estado")) {
        estado = server.arg("estado");
    }

    String binario = buscarEnModelo(estado);
    
    // ENVIAR AL ATMEGA: El println es vital para que readStringUntil lo detecte
    Serial.println(binario); 

    server.send(200, "application/json", "{\"data\":[\"" + binario + "\"]}");
}

void setup() {
    Serial.begin(9600);
    WiFi.begin(ssid, password);
    while (WiFi.status() != WL_CONNECTED) delay(500);
    server.on("/consultar", handleConsultar);
    server.begin();
}

void loop() {
    server.handleClient();
}
