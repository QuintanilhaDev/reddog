<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$rota = $_GET['rota'] ?? '';

if ($rota == "listar") {
    $transacoes = [
        ['id' => 1, 'usuario' => 'Maria Silva', 'valor' => 567.00, 'ip' => '200.189.105.136', 'risco' => 'BAIXO', 'criado_em' => '2023-05-20 14:23:45'],
        ['id' => 2, 'usuario' => 'João Santos', 'valor' => 1240.00, 'ip' => '177.234.678.12', 'risco' => 'MEDIO', 'criado_em' => '2023-05-20 13:45:22'],
        ['id' => 3, 'usuario' => 'Ana Costa', 'valor' => 890.50, 'ip' => '186.239.105.78', 'risco' => 'ALTO', 'criado_em' => '2023-05-20 12:31:18']
    ];
    
    echo json_encode($transacoes);
} else {
    echo json_encode(['error' => 'rota invalida']);
}
?>