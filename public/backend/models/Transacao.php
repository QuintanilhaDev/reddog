<?php
class Transacao {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function criar($dados) {
        $risco = $this->analisarRisco($dados);
        $stmt = $this->pdo->prepare("INSERT INTO transacoes (usuario, valor, ip, risco) VALUES (?, ?, ?, ?)");
        $stmt->execute([$dados['usuario'], $dados['valor'], $dados['ip'], $risco]);
        return ["status" => "ok", "risco" => $risco];
    }
    
    private function analisarRisco($dados) {
        if ($dados['valor'] > 1000) return "ALTO";
        if (strpos($dados['ip'], "192.168") !== false) return "SUSPEITO";
        return "BAIXO";
    }
    
    public function listar() {
        $stmt = $this->pdo->query("SELECT * FROM transacoes ORDER BY criado_em DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>