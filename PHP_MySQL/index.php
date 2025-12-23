<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Infrastructure\Persistence\DatabaseConnection;
use App\Infrastructure\Persistence\MySQLAdapter;
use App\Infrastructure\ExternalApi\Esp8266Adapter;
use App\Application\UpdateProcess;

// Configuración de entorno
date_default_timezone_set('America/Bogota');
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Configurar zona horaria global desde el entorno
date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'UTC');

// Inicialización de componentes
$pdo = DatabaseConnection::getConnection();
$dbAdapter = new MySQLAdapter($pdo);
$hwAdapter = new Esp8266Adapter();
$useCase = new UpdateProcess($hwAdapter, $dbAdapter);

// Lógica de procesamiento
$mensaje = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['estado'])) {
    $resultado = $useCase->execute($_POST['estado']);
    $mensaje = $resultado['success'] ? "✅ Comando enviado con éxito" : "❌ Error de conexión con hardware";
}

// Preparación de datos para el Template
$historial = $dbAdapter->getLatestLogs(15);
$estadosDisponibles = [
    "en_espera"   => "En Espera (0000)",
    "seleccionado" => "Seleccionado (0001)",
    "limpio"      => "Limpio (0011)",
    "molido"      => "Molido (0110)",
    "error"       => "Fallo Crítico (1111)"
];

// Carga del Template (Vista)
include __DIR__ . '/views/panel.php';
