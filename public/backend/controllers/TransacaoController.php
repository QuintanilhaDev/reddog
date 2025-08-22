<?php
require_once __DIR__ . "/../models/Transacao.php";

class TransacaoController {
    private $transacao;

    public function __construct($pdo) {
        $this->transacao = new Transacao($pdo);
    }

    public function criar($dados) {
        return $this->transacao->criar($dados);
    }

    public function listar() {
        return $this->transacao->listar();
    }
}