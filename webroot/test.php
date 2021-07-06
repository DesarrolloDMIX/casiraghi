<?php
echo '<pre>';
echo 'IP de nuestro servidor: ' . $_SERVER['SERVER_ADDR'] . ' <br>';

echo 'Url de la api: ' . ($url = 'https://casiraghi.procomisp.com.ar/v2/products') . '<br>';
$ch = curl_init($url);
// $ch = curl_init('https://casiraghi2.procomisp.com.ar/auth/login');
// $ch = curl_init('https://casiraghi2.procomisp.com.ar:5001/v2/auth/login');
// $ch = curl_init('https://casiraghi2.procomisp.com.ar:5011/v2/auth/login');

// $payload = [
//     'username' => 'ECOMMERCE',
//     'password' => 'ECOMMERCE',
//     'deviceinfo' => [
//         'model' => 'test',
//         'platform' => 'test',
//         'uuid' => 'test',
//         'version' => 'test',
//         'manufacturer' => 'test'
//     ]
// ];

// echo 'JSON enviado: <br>';
// echo json_encode($payload, JSON_PRETTY_PRINT);
// echo ' <hr>';

curl_setopt_array($ch, [
    // CURLOPT_POSTFIELDS => json_encode($payload),

    // CURLOPT_HTTPHEADER => ['Content-Type: application/json'],

    CURLOPT_RETURNTRANSFER => true,

    CURLOPT_SSL_VERIFYPEER => false
]);

$result = curl_exec($ch);

if ($result == false) {
    echo 'Error: ' . curl_error($ch);
    echo ' <br>';
    echo ' <br>';
    echo 'Código de error de curl: ' . curl_errno($ch);
    echo ' <br>';
    echo ' <br>';
    echo 'Error de curl: ' . curl_strerror(curl_errno($ch));
    echo ' <br>';
    echo ' <br>';
}

echo 'RESPUESTA: <br>';

echo $result != false ? $result : 'Sin respuesta';

curl_close($ch);
