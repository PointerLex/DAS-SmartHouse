# Smart Home Dashboard - Sistema de adquisición y distrubición de datos

Smart Home Dashboard es un proyecto desarrollado en Laravel para gestionar datos de sensores en tiempo real, enviar alertas y registrar datos en una base de datos.

## Requisitos previos

Antes de clonar y ejecutar este proyecto, asegúrate de que tienes instalados los siguientes programas:

- **PHP 8.2 o superior**
- **Composer**
- **Node.js y npm**
- **Git**
- **Servidor de base de datos MySQL**
- **Laravel CLI**

## Clonar el repositorio

```bash
# Clonar el repositorio desde GitHub
git clone https://github.com/PointerLex/DAS-SmartHouse.git

# Navegar al directorio del proyecto
cd DAS-SmartHouse
```

## Configuración inicial

### 1. Instalar dependencias de PHP

Ejecuta el siguiente comando para instalar las dependencias del proyecto:

```bash
composer install
```

### 2. Instalar dependencias de JavaScript

Ejecuta el siguiente comando para instalar las dependencias del frontend:

```bash
npm install
```

### 3. Configurar el archivo `.env`

Copia el archivo de entorno de ejemplo y configura tus variables:

```bash
cp .env.example .env
```

Edita el archivo `.env` y actualiza las siguientes variables según tu configuración:

```env
APP_NAME=SmartHome
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smarthome
DB_USERNAME=
DB_PASSWORD=

BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=your-app-cluster

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_FROM_ADDRESS=no-reply@smarthouse.com
MAIL_FROM_NAME="Smart House"
```

### 4. Generar la clave de la aplicación

```bash
php artisan key:generate
```

### 5. Configurar la base de datos

Asegúrate de que el servidor MySQL esté ejecutándose. Luego, ejecuta las migraciones para crear las tablas necesarias:

```bash
php artisan migrate
```

### 6. Compilar los assets

Compila los assets del frontend utilizando Vite:

```bash
npm run build
```

## Ejecución del proyecto

### Iniciar el servidor local

Ejecuta el servidor de desarrollo de Laravel:

```bash
php artisan serve
```

Esto ejecutará la aplicación en `http://localhost:8000`.

### Ejecutar el servidor de colas

Para manejar las notificaciones y eventos, ejecuta el siguiente comando en una nueva terminal:

```bash
php artisan queue:work
```

## Probar la funcionalidad del correo

Para probar el envío de correos electrónicos, asegúrate de haber configurado Mailtrap correctamente en el archivo `.env`.

## Funcionalidades principales

- Monitoreo en tiempo real de datos de sensores con Laravel Echo y Pusher.
- Registro de datos históricos de sensores en la base de datos.
- Envío de correos electrónicos de alerta cuando se desconecta un sensor.
- Alerta visual mediante SweetAlert2 en el frontend.

## Contribuciones

Si deseas contribuir al proyecto, por favor sigue los siguientes pasos:

1. Realiza un fork del repositorio.
2. Crea una nueva rama:
   ```bash
   git checkout -b feature/nueva-funcionalidad
   ```
3. Realiza tus cambios y haz commit:
   ```bash
   git commit -m "Agregar nueva funcionalidad"
   ```
4. Sube los cambios a tu rama:
   ```bash
   git push origin feature/nueva-funcionalidad
   ```
5. Crea un pull request.

## Licencia

Este proyecto está licenciado bajo la [MIT License](LICENSE).
