<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$transacoes = [];
$apiUrl = "http://localhost:8080/backend/routes/api-simulada.php?rota=listar";

function fetchData($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return $httpCode === 200 ? $response : false;
}

$apiData = fetchData($apiUrl);

if ($apiData !== false) {
    $transacoes = json_decode($apiData, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("Erro ao decodificar JSON: " . json_last_error_msg());
        $transacoes = [];
    }
}

if (empty($transacoes)) {
    $transacoes = [
        ['id' => 1, 'usuario' => 'Maria Silva', 'valor' => 567.00, 'ip' => '200.189.105.136', 'risco' => 'BAIXO', 'criado_em' => '2023-05-20 14:23:45'],
        ['id' => 2, 'usuario' => 'João Santos', 'valor' => 1240.00, 'ip' => '177.234.678.12', 'risco' => 'MEDIO', 'criado_em' => '2023-05-20 13:45:22'],
        ['id' => 3, 'usuario' => 'Ana Costa', 'valor' => 890.50, 'ip' => '186.239.105.78', 'risco' => 'ALTO', 'criado_em' => '2023-05-20 12:31:18'],
        ['id' => 4, 'usuario' => 'Pedro Alves', 'valor' => 350.00, 'ip' => '152.239.105.44', 'risco' => 'BAIXO', 'criado_em' => '2023-05-20 11:20:30'],
        ['id' => 5, 'usuario' => 'Carla Mendes', 'valor' => 2350.00, 'ip' => '201.49.105.136', 'risco' => 'ALTO', 'criado_em' => '2023-05-20 10:15:22']
    ];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RedDog - Sistema Antifraude</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #d63031;
            --primary-dark: #c23616;
            --secondary: #2d3436;
            --light: #f5f6fa;
            --success: #00b894;
            --warning: #fdcb6e;
            --danger: #d63031;
            --gray: #dfe6e9;
            --dark-gray: #636e72;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #0f172a;
            color: #f8fafc;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            border-bottom: 1px solid #334155;
            margin-bottom: 30px;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logo h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary);
        }
        
        .logo-icon {
            font-size: 32px;
            color: var(--primary);
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 20px;
        }
        
        .sidebar {
            background: #1e293b;
            border-radius: 15px;
            padding: 20px;
            height: fit-content;
        }
        
        .sidebar-menu {
            list-style: none;
        }
        
        .sidebar-menu li {
            margin-bottom: 10px;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: var(--primary);
            color: white;
        }
        
        .main-content {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .stat-card {
            background: #1e293b;
            border-radius: 15px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .bg-blue {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }
        
        .bg-green {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }
        
        .bg-red {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }
        
        .bg-purple {
            background: rgba(139, 92, 246, 0.2);
            color: #8b5cf6;
        }
        
        .stat-value {
            font-size: 28px;
            font-weight: 700;
        }
        
        .stat-title {
            color: #94a3b8;
            font-size: 14px;
        }
        
        .card {
            background: #1e293b;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .card-title {
            font-size: 20px;
            font-weight: 600;
        }
        
        .filters {
            display: flex;
            gap: 15px;
        }
        
        .filter-btn {
            background: #334155;
            border: none;
            color: #cbd5e1;
            padding: 8px 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .filter-btn:hover, .filter-btn.active {
            background: var(--primary);
            color: white;
        }
        
        .search-box {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .search-input {
            flex: 1;
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid #334155;
            background: #1e293b;
            color: white;
        }
        
        .search-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #334155;
        }
        
        th {
            color: #94a3b8;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
        }
        
        th:hover {
            color: white;
        }
        
        .risco {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            text-align: center;
            min-width: 70px;
        }
        
        .baixo {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }
        
        .medio {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
        }
        
        .alto {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }
        
        .actions {
            display: flex;
            gap: 10px;
        }
        
        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .action-btn:hover {
            transform: scale(1.1);
        }
        
        .view {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }
        
        .view:hover {
            background: rgba(59, 130, 246, 0.4);
        }
        
        .block {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }
        
        .block:hover {
            background: rgba(239, 68, 68, 0.4);
        }
        
        .refresh-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
        }
        
        .refresh-btn:hover {
            background: var(--primary-dark);
        }
        
        footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            color: #94a3b8;
            border-top: 1px solid #334155;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background: #1e293b;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .close-modal {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }
        
        @media (max-width: 1024px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                display: none;
            }
            
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .filters {
                flex-wrap: wrap;
            }
            
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <div class="logo-icon"><i class="fas fa-shield-dog"></i></div>
                <h1>RedDog Anti-Fraude</h1>
            </div>
            <div class="user-info">
                <div class="user-avatar"><i class="fas fa-user"></i></div>
                <div>
                    <div>Admin</div>
                    <div style="font-size: 12px; color: #94a3b8;">Administrador</div>
                </div>
            </div>
        </header>
        
        <div class="dashboard-grid">
            <aside class="sidebar">
                <ul class="sidebar-menu">
                    <li><a href="#" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="#"><i class="fas fa-exchange-alt"></i> Transações</a></li>
                    <li><a href="#"><i class="fas fa-user-slash"></i> Bloqueios</a></li>
                    <li><a href="#"><i class="fas fa-chart-bar"></i> Relatórios</a></li>
                    <li><a href="#"><i class="fas fa-bell"></i> Alertas</a></li>
                    <li><a href="#"><i class="fas fa-cog"></i> Configurações</a></li>
                    <li><a href="#"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
                </ul>
            </aside>
            
            <main class="main-content">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon bg-blue"><i class="fas fa-money-bill-wave"></i></div>
                        </div>
                        <div class="stat-value">R$ <?php 
                            $total = 0;
                            foreach ($transacoes as $t) {
                                $total += $t['valor'];
                            }
                            echo number_format($total, 2, ',', '.');
                        ?></div>
                        <div class="stat-title">Volume de Transações</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon bg-green"><i class="fas fa-check-circle"></i></div>
                        </div>
                        <div class="stat-value"><?php 
                            $aprovadas = 0;
                            foreach ($transacoes as $t) {
                                if ($t['risco'] == 'BAIXO') $aprovadas++;
                            }
                            echo $aprovadas;
                        ?></div>
                        <div class="stat-title">Transações Aprovadas</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon bg-red"><i class="fas fa-times-circle"></i></div>
                        </div>
                        <div class="stat-value"><?php 
                            $suspeitas = 0;
                            foreach ($transacoes as $t) {
                                if ($t['risco'] != 'BAIXO') $suspeitas++;
                            }
                            echo $suspeitas;
                        ?></div>
                        <div class="stat-title">Transações Suspeitas</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon bg-purple"><i class="fas fa-chart-line"></i></div>
                        </div>
                        <div class="stat-value"><?php 
                            $totalTransacoes = count($transacoes);
                            $taxaAprovacao = $totalTransacoes > 0 ? ($aprovadas / $totalTransacoes) * 100 : 0;
                            echo number_format($taxaAprovacao, 1) . '%';
                        ?></div>
                        <div class="stat-title">Taxa de Aprovação</div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Monitoramento de Transações</h2>
                        <button class="refresh-btn" onclick="location.reload()">
                            <i class="fas fa-sync-alt"></i> Atualizar
                        </button>
                    </div>
                    
                    <div class="filters">
                        <button class="filter-btn active">Hoje</button>
                        <button class="filter-btn">Semana</button>
                        <button class="filter-btn">Mês</button>
                        <button class="filter-btn" id="advancedFilter"><i class="fas fa-filter"></i> Filtro Avançado</button>
                    </div>
                    
                    <div class="search-box">
                        <input type="text" class="search-input" placeholder="Buscar por usuário, IP ou valor..." id="searchInput">
                        <button class="search-btn" onclick="filterTable()"><i class="fas fa-search"></i> Buscar</button>
                    </div>
                    
                    <div class="table-responsive">
                        <table id="transactionsTable">
                            <thead>
                                <tr>
                                    <th onclick="sortTable(0)">ID <i class="fas fa-sort"></i></th>
                                    <th onclick="sortTable(1)">Usuário <i class="fas fa-sort"></i></th>
                                    <th onclick="sortTable(2)">Valor <i class="fas fa-sort"></i></th>
                                    <th onclick="sortTable(3)">IP <i class="fas fa-sort"></i></th>
                                    <th onclick="sortTable(4)">Risco <i class="fas fa-sort"></i></th>
                                    <th onclick="sortTable(5)">Data <i class="fas fa-sort"></i></th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transacoes as $t): ?>
                                <tr>
                                    <td>#<?= $t['id'] ?></td>
                                    <td><?= $t['usuario'] ?></td>
                                    <td>R$ <?= number_format($t['valor'], 2, ',', '.') ?></td>
                                    <td><?= $t['ip'] ?></td>
                                    <td><span class="risco <?= strtolower($t['risco']) ?>"><?= $t['risco'] ?></span></td>
                                    <td><?= $t['criado_em'] ?></td>
                                    <td class="actions">
                                        <button class="action-btn view" onclick="viewTransaction(<?= $t['id'] ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn block" onclick="blockTransaction(<?= $t['id'] ?>)">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
        
        <footer>
            <p>RedDog Anti-Fraude &copy; 2023 - Sistema de prevenção a fraudes em e-commerce</p>
        </footer>
    </div>

    <!-- Modal para detalhes da transação -->
    <div class="modal" id="transactionModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detalhes da Transação</h2>
                <button class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            <div id="modalBody">
                <!-- Conteúdo será preenchido via JavaScript -->
            </div>
        </div>
    </div>

    <script>
        // Funções JavaScript para interatividade
        function viewTransaction(id) {
            // Simulação de dados detalhados (em um sistema real, buscaria da API)
            const transaction = {
                id: id,
                usuario: "Usuário " + id,
                valor: (100 * id).toFixed(2),
                ip: "192.168." + id + ".100",
                risco: id % 3 === 0 ? "ALTO" : (id % 2 === 0 ? "MEDIO" : "BAIXO"),
                data: new Date().toLocaleString(),
                localizacao: "São Paulo, BR",
                dispositivo: "Chrome/Windows 10",
                historico: (id % 4 === 0) ? "Comportamento suspeito" : "Histórico normal"
            };
            
            document.getElementById('modalBody').innerHTML = `
                <p><strong>ID:</strong> #${transaction.id}</p>
                <p><strong>Usuário:</strong> ${transaction.usuario}</p>
                <p><strong>Valor:</strong> R$ ${transaction.valor}</p>
                <p><strong>IP:</strong> ${transaction.ip}</p>
                <p><strong>Risco:</strong> <span class="risco ${transaction.risco.toLowerCase()}">${transaction.risco}</span></p>
                <p><strong>Data:</strong> ${transaction.data}</p>
                <p><strong>Localização:</strong> ${transaction.localizacao}</p>
                <p><strong>Dispositivo:</strong> ${transaction.dispositivo}</p>
                <p><strong>Histórico:</strong> ${transaction.historico}</p>
                <div style="margin-top: 20px;">
                    <button class="filter-btn" style="background: var(--primary); color: white; width: 100%;" onclick="closeModal()">Fechar</button>
                </div>
            `;
            
            document.getElementById('transactionModal').style.display = 'flex';
        }
        
        function blockTransaction(id) {
            if (confirm(`Tem certeza que deseja bloquear a transação #${id}?`)) {
                alert(`Transação #${id} bloqueada com sucesso!`);
                
                // Efeito visual de bloqueio
                const rows = document.querySelectorAll('#transactionsTable tbody tr');
                rows.forEach(row => {
                    if (row.cells[0].textContent === '#' + id) {
                        row.style.opacity = '0.6';
                        row.style.backgroundColor = 'rgba(239, 68, 68, 0.1)';
                    }
                });
            }
        }
        
        function closeModal() {
            document.getElementById('transactionModal').style.display = 'none';
        }
        
        function filterTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toUpperCase();
            const table = document.getElementById('transactionsTable');
            const tr = table.getElementsByTagName('tr');
            
            for (let i = 1; i < tr.length; i++) {
                let found = false;
                const td = tr[i].getElementsByTagName('td');
                
                for (let j = 0; j < td.length; j++) {
                    if (td[j]) {
                        if (td[j].textContent.toUpperCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                
                tr[i].style.display = found ? '' : 'none';
            }
        }
        
        function sortTable(column) {
            const table = document.getElementById('transactionsTable');
            let switching = true;
            let shouldSwitch, i;
            let switchCount = 0;
            let direction = 'asc';
            
            while (switching) {
                switching = false;
                const rows = table.rows;
                
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    const x = rows[i].getElementsByTagName('td')[column];
                    const y = rows[i + 1].getElementsByTagName('td')[column];
                    
                    if (direction === 'asc') {
                        if (x.textContent.toLowerCase() > y.textContent.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (direction === 'desc') {
                        if (x.textContent.toLowerCase() < y.textContent.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchCount++;
                } else {
                    if (switchCount === 0 && direction === 'asc') {
                        direction = 'desc';
                        switching = true;
                    }
                }
            }
        }
        
        // Filtros de data
        document.querySelectorAll('.filter-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Simulação de filtro por período
                const filterType = this.textContent.trim();
                alert(`Filtro aplicado: ${filterType}`);
            });
        });
        
        // Fechar modal clicando fora dele
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('transactionModal');
            if (event.target === modal) {
                closeModal();
            }
        });
        
        // Pesquisa ao pressionar Enter
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                filterTable();
            }
        });
        
        console.log("Dashboard RedDog carregado com sucesso!");
    </script>
</body>
</html>