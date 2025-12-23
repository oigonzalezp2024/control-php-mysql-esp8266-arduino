<?php
namespace App\Application;

class UpdateProcess {
    private $hw;
    private $db;

    public function __construct($hw, $db) {
        $this->hw = $hw;
        $this->db = $db;
    }

    public function execute(string $status): array {
        $response = $this->hw->transmit($status);
        $codigoBinario = $response['binary'] ?? "1111"; 

        $this->db->save(
            $status, 
            $codigoBinario, 
            $response['success']
        );

        return $response;
    }
}
