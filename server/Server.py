from flask import Flask, jsonify, request
import requests
import serial
import time

app = Flask(__name__)

# Configurar el puerto serie del Arduino
arduino_port = "COM3"
baud_rate = 9600
try:
    arduino = serial.Serial(arduino_port, baud_rate, timeout=1)
    print(f"Conectado al Arduino en el puerto {arduino_port}")
except Exception as e:
    print(f"No se pudo conectar al Arduino: {e}")
    arduino = None

# Enviar datos al endpoint API de Laravel
def send_to_api(sensor):
    url = "http://127.0.0.1:8000/api/sensor-readings"
    try:
        response = requests.post(url, json=sensor)
        print(f"Enviado: {sensor}, Respuesta: {response.status_code}")
    except Exception as e:
        print(f"Error al enviar los datos: {e}")

# Leer datos del Arduino y procesarlos
def read_sensor_data_from_arduino():
    if arduino and arduino.in_waiting > 0:
        try:
            line = arduino.readline().decode('utf-8').strip()  # Leer una línea y decodificar
            if line:
                print(f"Datos recibidos del Arduino: {line}")
                sensor_data = parse_sensor_data(line)
                return sensor_data
        except Exception as e:
            print(f"Error al leer datos del Arduino: {e}")
    return None

# Parsear los datos JSON recibidos desde el Arduino
def parse_sensor_data(line):
    try:
        data = line.split(';')  # Supongamos que los datos están separados por ";"
        sensor_type, value, status = data
        return {
            "sensor_type": sensor_type,
            "value": int(value),
            "status": status
        }
    except Exception as e:
        print(f"Error al parsear los datos: {e}")
        return None

# Ruta para probar el servidor
@app.route('/test', methods=['GET'])
def test_server():
    return jsonify({"message": "El servidor está funcionando correctamente"}), 200

# Ruta para enviar datos a Laravel
@app.route('/send-sensors', methods=['POST'])
def send_sensors():
    sensor_data = read_sensor_data_from_arduino()
    if sensor_data:
        send_to_api(sensor_data)
        return jsonify({"message": "Datos enviados exitosamente", "data": sensor_data}), 200
    else:
        return jsonify({"message": "No se recibieron datos del Arduino"}), 400

# Enviar datos periódicamente al servidor Laravel
if __name__ == '__main__':
    print("Servidor iniciado...")
    while True:
        sensor_data = read_sensor_data_from_arduino()
        if sensor_data:
            send_to_api(sensor_data)
        time.sleep(2)  # Tiempo entre envíos
