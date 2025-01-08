from flask import Flask, jsonify, request
import requests
import time
import random

app = Flask(__name__)

# Simular la lectura de datos del sensor con valores aleatorios
def read_sensor_data():
    sensors = [
        {
            "sensor_type": "gas",
            "value": random.randint(100, 500),  # Valor aleatorio entre 100 y 500
            "status": "Peligro" if random.random() < 0.3 else "Seguro"  # 30% de probabilidad de peligro
        },
        {
            "sensor_type": "luz",
            "value": random.randint(200, 800),  # Valor aleatorio entre 200 y 800
            "status": "Luz suficiente" if random.random() < 0.7 else "Luz insuficiente"  # 70% de probabilidad de luz suficiente
        }
    ]
    return sensors

# Enviar datos al endpoint API de Laravel
def send_to_api(sensor):
    url = "http://127.0.0.1:8000/api/sensor-readings"
    try:
        response = requests.post(url, json=sensor)
        print(f"Enviado: {sensor}, Respuesta: {response.status_code}")
    except Exception as e:
        print(f"Error al enviar los datos: {e}")

# Ruta para probar el servidor
@app.route('/test', methods=['GET'])
def test_server():
    return jsonify({"message": "El servidor está funcionando correctamente"}), 200

# Ruta para enviar datos simulados a Laravel
@app.route('/send-sensors', methods=['POST'])
def send_sensors():
    data = read_sensor_data()
    for sensor in data:
        send_to_api(sensor)
    return jsonify({"message": "Datos enviados exitosamente"}), 200

# Enviar datos cada 10 segundos (se ejecuta en segundo plano)
if __name__ == '__main__':
    print("Servidor iniciado...")
    while True:
        data = read_sensor_data()
        for sensor in data:
            send_to_api(sensor)
        time.sleep(10)
