<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../controllers/TransacaoController.php";

$controller = new TransacaoController($pdo);
$rota = $_GET['rota'] ?? '';
header("Content-Type: application/json");

if ($rota == "criar" && $_SERVER['REQUEST_METHOD'] == "POST") {
    $dados = json_decode(file_get_contents("php://input"), true);
    echo json_encode($controller->criar($dados));
} elseif ($rota == "listar") {
    echo json_encode($controller->listar());
} else {
    echo json_encode(['error' => 'rota invalida']);
}
?>