#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <ArduinoJson.h>

// Inicialización del LCD
LiquidCrystal_I2C lcd(0x27, 16, 2);

// Definición de pines
int MQ2Gas = A0;            // Pin del sensor de gas
int valorGas = 0;           // Valor leído del sensor de gas
int valorGasCalibrado = 0;  // Valor base después de la calibración

int LED = 5;         // Pin del LED
int valorLuz = A1;   // Pin del sensor de luz (fotodiodo)
int lecturaLuz = 0;  // Valor leído del sensor de luz
int boton = 4;       // Pin del botón para calibrar

// Variables para manejo de temporización
unsigned long previousMillis = 0;    // Almacena el último tiempo de actualización
const unsigned long interval = 500;  // Intervalo de actualización en milisegundos

// Variables globales para calibración
bool calibrando = false;
unsigned long startCalibracionMillis = 0;
const unsigned long tiempoCalibracion = 2000;  // Duración de la calibración en milisegundos

// Almacén de valores anteriores para evitar actualizaciones innecesarias en el LCD
int lastGasValue = -1;
int lastLightValue = -1;

void setup() {
    // Configuración inicial del sensor de gas
    pinMode(MQ2Gas, INPUT);

    // Configuración inicial del LED
    pinMode(LED, OUTPUT);

    // Configuración del botón
    pinMode(boton, INPUT_PULLUP);  // Botón configurado como entrada con resistencia pull-up

    // Configuración de la comunicación serie
    Serial.begin(9600);

    // Configuración inicial del LCD
    lcd.init();
    lcd.backlight();

    // Mostrar mensaje de inicio
    lcd.setCursor(0, 0);
    lcd.print("Iniciando...");
    delay(2000);  // Tiempo para leer el mensaje inicial
    lcd.clear();
}

void loop() {
    // Leer el estado del botón
    int estadoBoton = digitalRead(boton);

    // Si se presiona el botón, iniciar la calibración
    if (estadoBoton == LOW && !calibrando) {
        iniciarCalibracion();
    }

    // Si está en proceso de calibración, manejarla
    if (calibrando) {
        manejarCalibracion();
        return;  // Salir del loop para no interferir con otras operaciones
    }

    // Actualizar sensores en intervalos
    if (millis() - previousMillis >= interval) {
        previousMillis = millis();  // Actualiza el tiempo previo
        leerSensores();
    }
}

void leerSensores() {
    // Leer valores de los sensores
    valorGas = analogRead(MQ2Gas);
    lecturaLuz = analogRead(valorLuz);

    actualizarLCD(valorGas, lecturaLuz);
    enviarDatosJSON(valorGas, lecturaLuz);

    lastGasValue = valorGas;
    lastLightValue = lecturaLuz;

    // Control del LED basado en la luz
    if (lecturaLuz < 200) {
        digitalWrite(LED, HIGH);
    } else {
        digitalWrite(LED, LOW);
    }
}

void actualizarLCD(int gasValue, int lightValue) {
    // Mostrar información del gas y su estado
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Gas: ");
    lcd.print(gasValue);
    if (gasValue > 300) {
        lcd.print(" Peligro");
    } else {
        lcd.print(" Seguro ");
    }

    // Mostrar información de la luz y su estado
    lcd.setCursor(0, 1);
    lcd.print("Luz: ");
    lcd.print(lightValue);
    if (lightValue < 200) {
        lcd.print(" Insuf.");
    } else {
        lcd.print(" Sufic.");
    }
}

void enviarDatosJSON(int gasValue, int lightValue) {
    StaticJsonDocument<200> json;

    // Datos del sensor de gas
    json["sensor_type"] = "gas";
    json["value"] = gasValue;
    json["status"] = (gasValue > 300) ? "Peligro" : "Seguro";
    serializeJson(json, Serial);
    Serial.println();

    // Datos del sensor de luz
    json["sensor_type"] = "luz";
    json["value"] = lightValue;
    json["status"] = (lightValue < 200) ? "Luz insuficiente" : "Luz suficiente";
    serializeJson(json, Serial);
    Serial.println();
}

void iniciarCalibracion() {
    calibrando = true;
    startCalibracionMillis = millis();

    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Calibrando...");
    Serial.println("Iniciando calibracion...");
}

void manejarCalibracion() {
    if (millis() - startCalibracionMillis >= tiempoCalibracion) {
        // Finalizar calibración
        valorGasCalibrado = analogRead(MQ2Gas);
        calibrando = false;

        lcd.setCursor(0, 1);
        lcd.print("Calibracion OK");
        Serial.print("Valor calibrado: ");
        Serial.println(valorGasCalibrado);
    }
}
