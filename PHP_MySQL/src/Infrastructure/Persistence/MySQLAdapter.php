<?php
namespace App\Infrastructure\Persistence;

use PDO;
use PDOException;
use DateTime;
use DateTimeZone;

class MySQLAdapter {
    private $db;
    private string $timezone;

    public function __construct(PDO $connection) {
        $this->db = $connection;
        // Leemos la zona horaria del .env o usamos UTC por defecto
        $this->timezone = $_ENV['APP_TIMEZONE'] ?? 'UTC';
    }

    public function save(string $status, string $binary, bool $success): void {
        try {
            // Generar fecha actual basada en la configuración del entorno
            $date = new DateTime('now', new DateTimeZone($this->timezone));
            $fechaLocal = $date->format('Y-m-d H:i:s');

            $sql = "INSERT INTO historial (estado, codigo_binario, fue_exitoso, fecha) 
                    VALUES (:estado, :binario, :success, :fecha)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':estado'  => $status,
                ':binario' => $binary,
                ':success' => $success ? 1 : 0,
                ':fecha'   => $fechaLocal
            ]);
            
        } catch (PDOException $e) {
            error_log("Error al guardar en historial: " . $e->getMessage());
        }
    }

    public function getLatestLogs(int $limit = 10): array {
        try {
            $stmt = $this->db->prepare("SELECT * FROM historial ORDER BY fecha DESC LIMIT :limit");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al recuperar historial: " . $e->getMessage());
            return [];
        }
    }
}
