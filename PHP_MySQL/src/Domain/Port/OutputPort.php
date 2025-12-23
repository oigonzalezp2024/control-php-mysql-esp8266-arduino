<?php
namespace App\Domain\Port;

interface OutputPort {
    public function sendToHardware(string $status): string;
    public function recordHistory(string $status, string $binary, bool $success): void;
}
