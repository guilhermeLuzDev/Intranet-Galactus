<?php
session_start();

// Redireciona para agenda.php se já estiver logado
if (isset($_SESSION['user_id'])) {
    header("Location: agenda.php");
    exit();
}

// Definir o limite máximo de tentativas
$tentativas_max = 10;
if (!isset($_SESSION['tentativas'])) {
    $_SESSION['tentativas'] = 0;
}

// Configurações de conexão com o banco de dados
$host = 'localhost';
$dbname = 'ramal_db';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $error = "Erro na conexão. Contate o suporte.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = trim($_POST['usuario']);
    $senha = trim($_POST['senha']);

    if (empty($usuario) || empty($senha)) {
        $erro = "Por favor, preencha todos os campos.";
    } elseif ($_SESSION['tentativas'] >= $tentativas_max) {
        $erro = "Limite de tentativas atingido. Contate a equipe de TI.";
    } else {
        $stmt = $conn->prepare("SELECT id, usuario, senha, role FROM usuario WHERE usuario = :usuario");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $senha === $user['senha']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['usuario'] = $user['usuario'];
            $_SESSION['tentativas'] = 0;
            $_SESSION['last_activity'] = time(); // Inicializa o timestamp
            error_log("Login bem-sucedido - user_id: " . $_SESSION['user_id'] . ", usuario: " . $_SESSION['usuario']);
            header('Location: agenda.php');
            exit();
        } else {
            $_SESSION['tentativas']++;
            $erro = "Usuário ou senha incorretos. Tentativas restantes: " . ($tentativas_max - $_SESSION['tentativas']);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Galactus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Estilos mantidos iguais ao original */
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f8f9fa, #e0f7fa);
            min-height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-top: 70px;
            padding-bottom: 70px;
        }
        .login-container {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .navbar {
            background: linear-gradient(90deg, #295959, #2a9d8f);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 10px 20px;
        }
        .navbar-brand img {
            max-height: 40px;
        }
        .card-header {
            background: linear-gradient(90deg, #295959, #2a9d8f);
            color: #fff;
            border-radius: 15px 15px 0 0;
            padding: 15px;
            text-align: center;
        }
        .btn-primary {
            background: linear-gradient(90deg, #295959, #2a9d8f);
            border: none;
            border-radius: 10px;
            padding: 10px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #1e4040, #207d6c);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .alert {
            border-radius: 10px;
        }
        .btn-link {
            color: #295959;
        }
        .btn-link:hover {
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
            body { padding-top: 60px; padding-bottom: 60px; }
            .navbar { padding: 10px; }
            .login-container { padding: 15px; margin: 15px; }
            .card-header { padding: 10px; }
            .card-header img { max-width: 60px; }
            .card-header h3 { font-size: 1.2rem; }
            .btn-primary, .btn-link { padding: 8px; font-size: 0.9rem; }
            h6 { font-size: 0.9rem; }
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
        </div>
    </nav>

    <div class="login-container">
        <div class="card">
            <div class="card-header">
                <img src="img/logosemfundo.png" alt="Logo" class="img-fluid" style="max-width: 80px;">
                <h3>Setor de TI</h3>
            </div>
            <div class="card-body">
                <?php if (isset($_GET['timeout']) && $_GET['timeout'] == 1): ?>
                    <div class="alert alert-warning">Sessão expirada por inatividade. Faça login novamente.</div>
                <?php endif; ?>
                <?php if (isset($erro)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="usuario" class="form-label"></label>
                        <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuário" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label"></label>
                        <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                        <a href="agenda.php" class="btn btn-link mt-2">Voltar para Agenda</a>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <h6>@Grupogalactus</h6>
                </div>
            </div>
        </div>
    </div>

    <footer>
        © 2025 Galactus | Desenvolvido pela equipe de TI
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>