<?php
session_start();

// Define o limite de inatividade (15 minutos = 900 segundos)
$timeout = 900;

// Atualiza o timestamp da última atividade
$_SESSION['last_activity'] = time();

// Verifica se o tempo de inatividade foi excedido
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_unset();
    session_destroy();
    header("Location: loginti.php?timeout=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planilhas - Galactus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Estilos mantidos iguais ao original */
        body {
            font-family: 'Roboto', sans-serif;
            background: #f8f9fa;
            padding-top: 70px;
            margin-bottom: 70px;
        }
        .navbar {
            background: linear-gradient(90deg, #295959, #2a9d8f);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 10px 20px;
        }
        .navbar-brand img {
            max-height: 40px;
        }
        .user-info {
            color: #fff;
            margin-right: 20px;
            font-weight: 500;
        }
        .btn-login, .btn-logout {
            border-radius: 10px;
            padding: 5px 15px;
            transition: all 0.3s ease;
        }
        .btn-login {
            background: #fff;
            color: #295959;
        }
        .btn-login:hover {
            background: #e0e0e0;
            transform: translateY(-2px);
        }
        .btn-logout {
            background: #dc3545;
            color: #fff;
            border: none;
        }
        .btn-logout:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .planilhas-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .planilhas-container h1 {
            font-weight: 600;
            color: #295959;
            text-align: center;
            margin-bottom: 30px;
        }
        .planilhas-list {
            list-style: none;
            padding: 0;
        }
        .planilhas-list li {
            margin-bottom: 15px;
        }
        .planilhas-list li a {
            display: inline-flex;
            align-items: center;
            color: #295959;
            text-decoration: none;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }
        .planilhas-list li a i {
            margin-right: 10px;
            color: #2a9d8f;
        }
        .planilhas-list li a:hover {
            color: #2a9d8f;
            text-decoration: underline;
        }
        footer {
            background: #295959;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            width: 100%;
            position: fixed;
            bottom: 0;
        }
        @media (max-width: 768px) {
            .navbar { padding: 10px; }
            .user-info { font-size: 0.9rem; margin-right: 10px; }
            .btn-login, .btn-logout { padding: 5px 10px; font-size: 0.9rem; }
            .planilhas-container { padding: 15px; margin: 15px; }
            .planilhas-container h1 { font-size: 1.5rem; }
            .planilhas-list li a { font-size: 1rem; }
            footer { font-size: 0.8rem; padding: 8px 0; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="index.php">
                <img src="img/logosemfundo.png" alt="Logo">
            </a>
            <div class="d-flex align-items-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="user-info">Bem-vindo, <?php echo isset($_SESSION['usuario']) ? htmlspecialchars($_SESSION['usuario']) : 'Usuário Desconhecido'; ?>! (Logado)</span>
                    <a href="logout.php" class="btn btn-logout">Sair</a>
                <?php else: ?>
                    <span class="user-info">Você não está logado</span>
                    <a href="login.php" class="btn btn-login">Entrar</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="planilhas-container">
            <h1>Planilhas Corporativas</h1>
            <ul class="planilhas-list">
                <li>
                    <a href="planilhas/Formulário de aquisição e movimentação de ativo V0.xlsx" target="_blank">
                        <i class="bi bi-table"></i> Formulário de aquisição e movimentação de ativo
                    </a>
                </li>
                <li>
                    <a href="planilhas/Horas Extras.xlsx" target="_blank">
                        <i class="bi bi-table"></i> Autorização Horas Extras - Galactus
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <footer>
        © 2025 Galactus | Desenvolvido pela equipe de TI
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Função para atualizar a última atividade
        function updateActivity() {
            fetch('keep_alive.php', { method: 'POST' });
        }

        // Atualiza a cada interação do usuário
        document.addEventListener('mousemove', updateActivity);
        document.addEventListener('keypress', updateActivity);

        // Verifica o timeout no frontend (15 minutos = 900000 ms)
        setTimeout(function() {
            window.location.href = 'loginti.php?timeout=1';
        }, 900000);
    </script>
</body>
</html>