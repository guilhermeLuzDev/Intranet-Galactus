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

// Depuração: Verificar o conteúdo da sessão
error_log("Sessão em agenda.php: " . print_r($_SESSION, true));

// Conexão com o banco de dados
$conn = new PDO("mysql:host=localhost;dbname=ramal_db", "root", "");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Inicializa os parâmetros de busca
$usuario = isset($_GET['usuario']) ? trim($_GET['usuario']) : '';
$ramal = isset($_GET['ramal']) ? trim($_GET['ramal']) : '';
$email = isset($_GET['email']) ? trim($_GET['email']) : '';
$escritorio = isset($_GET['escritorio']) ? trim($_GET['escritorio']) : '';
$escritorio_filtro = isset($_GET['escritorio_filtro']) ? trim($_GET['escritorio_filtro']) : '';

// Monta a consulta SQL com filtros
$sql = "SELECT * FROM ramal WHERE 1=1";
$params = [];

if (!empty($usuario)) {
    $sql .= " AND usuario LIKE :usuario";
    $params[':usuario'] = "%$usuario%";
}
if (!empty($ramal)) {
    $sql .= " AND ramal LIKE :ramal";
    $params[':ramal'] = "%$ramal%";
}
if (!empty($email)) {
    $sql .= " AND email LIKE :email";
    $params[':email'] = "%$email%";
}
if (!empty($escritorio)) {
    $sql .= " AND escritorio LIKE :escritorio";
    $params[':escritorio'] = "%$escritorio%";
}
if (!empty($escritorio_filtro) && $escritorio_filtro !== 'todos') {
    $sql .= " AND escritorio = :escritorio_filtro";
    $params[':escritorio_filtro'] = $escritorio_filtro;
}

// Executa a consulta
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$contatos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda - Galactus</title>
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
        .btn-primary {
            background: linear-gradient(90deg, #295959, #2a9d8f);
            border: none;
            border-radius: 10px;
            padding: 5px 10px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #1e4040, #207d6c);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .btn-danger {
            background: #dc3545;
            border: none;
            border-radius: 10px;
            padding: 5px 10px;
            transition: all 0.3s ease;
        }
        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .table {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .filter-form {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: 500;
            color: #295959;
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
            .filter-form { padding: 15px; }
            .filter-form .row { margin-bottom: 0; }
            .filter-form .col-md-3 { margin-bottom: 15px; }
            h1 { font-size: 1.5rem; }
            .table th, .table td { font-size: 0.85rem; padding: 8px; }
            .btn-primary, .btn-danger { padding: 5px 8px; font-size: 0.85rem; }
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
                    <span class="user-info">Bem-vindo, <?php echo isset($_SESSION['usuario']) ? htmlspecialchars($_SESSION['usuario']) : 'Usuário Desconhecido'; ?>!</span>
                    <a href="logout.php" class="btn btn-logout">Sair</a>
                <?php else: ?>
                    <span class="user-info">Você não está logado</span>
                    <a href="loginti.php" class="btn btn-login">Entrar</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success">Ramal cadastrado com sucesso!</div>
        <?php endif; ?>
        
        <!-- Formulário de Filtro -->
        <div class="filter-form">
            <form method="GET" action="">
                <div class="row g-3">
                    <div class="col-md-3 col-12">
                        <label for="usuario" class="form-label">Usuário</label>
                        <input type="text" class="form-control" id="usuario" name="usuario" value="<?php echo htmlspecialchars($usuario); ?>" placeholder="Filtrar por usuário">
                    </div>
                    <div class="col-md-3 col-12">
                        <label for="ramal" class="form-label">Ramal</label>
                        <input type="text" class="form-control" id="ramal" name="ramal" value="<?php echo htmlspecialchars($ramal); ?>" placeholder="Filtrar por ramal">
                    </div>
                    <div class="col-md-3 col-12">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Filtrar por email">
                    </div>
                    <div class="col-md-3 col-12">
                        <label for="escritorio" class="form-label">Escritório (Busca)</label>
                        <input type="text" class="form-control" id="escritorio" name="escritorio" value="<?php echo htmlspecialchars($escritorio); ?>" placeholder="Filtrar por escritório">
                    </div>
                </div>
                <div class="row g-3 mt-2">
                    <div class="col-md-3 col-12">
                        <label for="escritorio_filtro" class="form-label">Filtrar por Escritório</label>
                        <select class="form-select" id="escritorio_filtro" name="escritorio_filtro">
                            <option value="todos" <?php echo $escritorio_filtro === 'todos' ? 'selected' : ''; ?>>Todos</option>
                            <option value="Galactus" <?php echo $escritorio_filtro === 'Galactus' ? 'selected' : ''; ?>>Galactus</option>
                            <option value="Tabu" <?php echo $escritorio_filtro === 'Tabu' ? 'selected' : ''; ?>>Tabu</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-12 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Botão para Criar Novo Contato -->
        <div class="text-end mb-3">
            <a href="cadastro.php" class="btn btn-primary">Criar Novo Contato</a>
        </div>

        <!-- Tabela de Contatos -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Ramal</th>
                        <th>Usuário</th>
                        <th>Email</th>
                        <th>Setor</th>
                        <th>Escritório</th>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <th>Ações</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($contatos) > 0): ?>
                        <?php foreach ($contatos as $contato): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($contato['ramal']); ?></td>
                                <td><?php echo htmlspecialchars($contato['usuario']); ?></td>
                                <td><?php echo htmlspecialchars($contato['email']); ?></td>
                                <td><?php echo htmlspecialchars($contato['setor']); ?></td>
                                <td><?php echo htmlspecialchars($contato['escritorio']); ?></td>
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <td>
                                        <a href="editar.php?id=<?php echo $contato['id']; ?>" class="btn btn-primary btn-sm me-2">Editar</a>
                                        <button class="btn btn-danger btn-sm" onclick="confirmarExclusao(<?php echo $contato['id']; ?>)">Excluir</button>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?php echo isset($_SESSION['user_id']) ? '6' : '5'; ?>" class="text-center">Nenhum ramal encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        © 2025 Galactus | Desenvolvido pela equipe de TI
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmarExclusao(id) {
            if (confirm("Tem certeza que deseja excluir este ramal?")) {
                window.location.href = "excluir.php?id=" + id;
            }
        }

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