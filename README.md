# 🏭 Control Industrial Hexagonal (PHP + Arduino + ESP8266)

Este proyecto implementa un sistema de control de procesos industriales utilizando **Arquitectura Hexagonal (Ports & Adapters)**. Permite enviar comandos desde una interfaz web hacia un ecosistema de hardware y registrar cada evento en una base de datos con precisión de zona horaria local.

## 🛠️ Ecosistema Tecnológico

* **Backend:** PHP 8.1+ (Strict Typing, PSR-4 Autoloading).
* **Arquitectura:** Hexagonal (Separación total de lógica de negocio e infraestructura).
* **Hardware:** * **ESP8266:** Actúa como puente Wi-Fi / Servidor API.
* **Arduino:** Control de bajo nivel para actuadores y sensores.


* **Base de Datos:** MySQL / MariaDB.
* **Frontend:** PHP Template Engine con Bootstrap 5.

## 📂 Estructura del Sistema

```text
├── src/
│   ├── Application/          # Casos de Uso (UpdateProcess.php)
│   ├── Infrastructure/
│   │   ├── Persistence/      # MySQL (Adapter y Connection)
│   │   └── ExternalApi/      # Comunicación con ESP8266 (Adapter)
├── views/
│   └── panel.php             # Template de la interfaz de usuario
├── .env                      # Configuración sensible (IPs, DB)
├── composer.json             # Dependencias (Dotenv, Autoload)
└── index.php                 # Orquestador principal (Bootstrap)

```

## 🔌 Integración de Hardware

El sistema sigue un flujo de comunicación bidireccional:

1. **PHP → ESP8266:** Envía un JSON con el comando solicitado (ej: `{"data": [["molido"]]}`).
2. **ESP8266 → Arduino:** El ESP8266 transmite el comando al Arduino (vía Serial o I2C).
3. **Arduino → PHP:** El Arduino procesa la acción y devuelve el código binario real de los sensores, el cual PHP registra en el historial.

## 🧠 Lógica de Diseño

* **Inyección de Dependencias:** El caso de uso no sabe qué motor de base de datos usa; solo recibe un objeto que sepa "guardar".
* **Zona Horaria:** Implementación forzada de `America/Bogota` desde PHP para garantizar la integridad de los logs en Colombia.
* **Robustez:** El sistema detecta fallos de conexión con el hardware y registra automáticamente un estado de error (`1111`) sin detener la aplicación.
