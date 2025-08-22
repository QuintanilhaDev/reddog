<?php
$dados = file_get_contents("http://localhost/RedDog/backend/routes/api.php?rota=listar");
$transacoes = json_decode($dados, true);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>RedDog - Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Monitoramento de Transações</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Usuário</th>
            <th>Valor</th>
            <th>IP</th>
            <th>Risco</th>
            <th>Data</th>
        </tr>
        <?php foreach ($transacoes as $t): ?>
        <tr>
            <td><?= $t['id'] ?></td>
            <td><?= $t['usuario'] ?></td>
            <td>R$ <?= number_format($t['valor'], 2, ',', '.') ?></td>
            <td><?= $t['ip'] ?></td>
            <td class="<?= strtolower($t['risco']) ?>"><?= $t['risco'] ?></td>
            <td><?= $t['criado_em'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>