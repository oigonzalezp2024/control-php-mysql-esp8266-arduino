<?php
namespace App\Domain\Port;

interface MySQLAdapterInterface {
    public function save(string $status, string $binary, bool $success): void;
    public function getLatestLogs(int $limit = 10): array;
}
