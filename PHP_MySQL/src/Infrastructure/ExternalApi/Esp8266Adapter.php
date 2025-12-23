<?php
namespace App\Infrastructure\ExternalApi;

class Esp8266Adapter {
    private string $url;

    public function __construct() {
        $ip   = $_ENV['ESP8266_IP'] ?? '192.168.101.29';
        $path = $_ENV['ESP8266_PATH'] ?? '/consultar';
        $this->url = "http://{$ip}{$path}";
    }

    public function transmit(string $status): array {
        $result = false;
        $httpCode = 0;
        $error = "Sin errores";
        
        $data = ["data" => [[$status]]];
        $jsonData = json_encode($data);

        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ]);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            $error = curl_error($ch);
        }
        curl_close($ch);

        $response = [
            'binary'   => "1111", 
            'success'  => false,
            'response' => $error
        ];

        if ($httpCode === 200 && $result !== false) {
            $decoded = json_decode($result, true);
            if (isset($decoded['data'][0][0])) {
                $response['binary']  = (string)$decoded['data'][0][0];
                $response['success'] = true;
                $response['response'] = $result;
            }
        }

        return $response;
    }
}
