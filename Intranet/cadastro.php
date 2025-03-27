<?php
session_start();

// Verifica se o usuário está logado e tem permissão (admin ou usuario)
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'usuario'])) {
    header("Location: loginti.php");
    exit();
}

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

// Depuração: Verificar o conteúdo da sessão
error_log("Sessão em cadastro.php: " . print_r($_SESSION, true));

// Conexão com o banco de dados e processamento do formulário
$conn = new PDO("mysql:host=localhost;dbname=ramal_db", "root", "");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ramal = trim($_POST['ramal']);
    $usuario = trim($_POST['usuario']);
    $email = trim($_POST['email']);
    $setor = trim($_POST['setor']);
    $escritorio = trim($_POST['escritorio']);

    if (empty($ramal) || empty($usuario) || empty($email) || empty($setor) || empty($escritorio)) {
        $error = "Todos os campos são obrigatórios!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Por favor, insira um email válido!";
    } else {
        try {
            // Verifica se o ramal já existe
            $stmt = $conn->prepare("SELECT COUNT(*) FROM ramal WHERE ramal = :ramal");
            $stmt->execute([':ramal' => $ramal]);
            if ($stmt->fetchColumn() > 0) {
                $error = "Este ramal já está cadastrado!";
            } else {
                $stmt = $conn->prepare("INSERT INTO ramal (ramal, usuario, email, setor, escritorio) VALUES (:ramal, :usuario, :email, :setor, :escritorio)");
                $stmt->execute([
                    ':ramal' => $ramal,
                    ':usuario' => $usuario,
                    ':email' => $email,
                    ':setor' => $setor,
                    ':escritorio' => $escritorio
                ]);
                header("Location: agenda.php?success=1");
                exit();
            }
        } catch (PDOException $e) {
            $error = "Erro ao cadastrar o ramal: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Contatos</title>
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
            background: #295959;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand img {
            max-height: 40px;
        }
        .navbar h2 {
            font-size: 1.25rem;
            color: #fff;
            margin: 0;
        }
        .user-info {
            color: #fff;
            margin-right: 20px;
            font-weight: 500;
        }
        .btn-logout {
            background: #dc3545;
            border: none;
            border-radius: 8px;
            padding: 5px 15px;
            color: #fff;
            transition: all 0.3s ease;
        }
        .btn-logout:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .form-header h3 {
            font-weight: 600;
            color: #295959;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 10px;
            transition: border-color 0.3s ease;
        }
        .form-control:focus {
            border-color: #2a9d8f;
            box-shadow: 0 0 5px rgba(42, 157, 143, 0.3);
        }
        .btn-primary {
            background: #2a9d8f;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            transition: background 0.3s ease;
        }
        .btn-primary:hover {
            background: #295959;
        }
        .btn-secondary {
            background: #6c757d;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            color: #fff;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .alert {
            border-radius: 8px;
            margin-top: 10px;
        }
        footer {
            background: #295959;
            color: #fff;
            text-align: center;
            padding: 1rem 0;
            width: 100%;
            position: fixed;
            bottom: 0;
        }
        @media (max-width: 768px) {
            .navbar { padding: 10px; }
            .navbar h2 { font-size: 1rem; }
            .user-info { font-size: 0.9rem; margin-right: 10px; }
            .btn-logout { padding: 5px 10px; font-size: 0.9rem; }
            .form-container { padding: 15px; margin: 15px; }
            .form-header h3 { font-size: 1.5rem; }
            .btn-primary, .btn-secondary { padding: 8px 15px; font-size: 0.9rem; }
            footer { font-size: 0.8rem; padding: 8px 0; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="agenda.php">
                <img src="./img/logogalactus.jpg" alt="Logo">
            </a>
            <h2></h2>
            <div class="d-flex align-items-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="user-info">Bem-vindo, <?php echo isset($_SESSION['usuario']) ? htmlspecialchars($_SESSION['usuario']) : 'Usuário Desconhecido'; ?>!</span>
                    <a href="logout.php" class="btn btn-logout">Sair</a>
                <?php else: ?>
                    <span class="user-info">Usuário não logado</span>
                    <a href="loginti.php" class="btn btn-login">Entrar</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="form-container">
            <div class="form-header">
                <h3>Novo Ramal</h3>
            </div>
            <?php
            if (isset($error)) {
                echo '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>' . htmlspecialchars($error) . '</div>';
            }
            if (isset($_GET['success']) && $_GET['success'] == 1) {
                echo '<div class="alert alert-success"><i class="bi bi-check-circle-fill me-2"></i>Ramal cadastrado com sucesso!</div>';
            }
            ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="ramal" class="form-label">Ramal</label>
                    <input type="text" class="form-control" id="ramal" name="ramal" required>
                </div>
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuário</label>
                    <input type="text" class="form-control" id="usuario" name="usuario" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="setor" class="form-label">Setor</label>
                    <input type="text" class="form-control" id="setor" name="setor" required>
                </div>
                <div class="mb-3">
                    <label for="escritorio" class="form-label">Escritório</label>
                    <select class="form-select" id="escritorio" name="escritorio" required>
                        <option value="">Selecione o escritório</option>
                        <option value="Galactus">Galactus</option>
                        <option value="Tabu">Tabu</option>
                    </select>
                </div>
                <div class="d-flex flex-column flex-md-row justify-content-between gap-2">
                    <button type="submit" class="btn btn-primary w-100 w-md-auto">Cadastrar Ramal</button>
                    <a href="agenda.php" class="btn btn-secondary w-100 w-md-auto">Voltar</a>
                </div>
            </form>
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