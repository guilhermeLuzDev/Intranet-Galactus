<?php
session_start();

// Definir o limite máximo de tentativas
$tentativas_max = 10;

// Verificar se a variável de tentativas está na sessão, caso contrário, inicializá-la
if (!isset($_SESSION['tentativas'])) {
    $_SESSION['tentativas'] = 0;
}

// Configurações de conexão com o banco de dados
$host = 'localhost';
$dbname = 'ramal_db';
$username = 'root';
$password = '';

try {
    // Conexão usando PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
    exit();
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar se o limite de tentativas foi alcançado
    if ($_SESSION['tentativas'] >= $tentativas_max) {
        $erro = "Acesso restrito a equipe de RH. Dúvida contatar o setor!";
    } else {
        // Receber e sanitizar os dados de entrada
        $usuario = trim($_POST['usuario']);
        $senha = trim($_POST['senha']);

        // Verificar se os campos não estão vazios
        if (empty($usuario) || empty($senha)) {
            $erro = "Por favor, preencha todos os campos.";
        } else {
            // Preparar a consulta para buscar o usuário
            $stmt = $conn->prepare("SELECT * FROM usuario WHERE usuario = :usuario");
            $stmt->bindParam(':usuario', $usuario);
            $stmt->execute();

            // Verificar se o usuário existe
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verificar a senha (comparação direta, pois está em texto simples no banco)
                if ($senha === $user['senha']) {
                    // Login bem-sucedido, resetar tentativas e iniciar sessão
                    $_SESSION['usuario'] = $usuario;
                    $_SESSION['tentativas'] = 0; // Resetar tentativas
                    header('Location: upload.php'); // Redirecionar para a página de ramais
                    exit();
                } else {
                    // Senha incorreta, incrementar tentativas
                    $_SESSION['tentativas']++;
                    $erro = "Usuário ou senha incorretos.";
                }
            } else {
                // Usuário não encontrado, incrementar tentativas
                $_SESSION['tentativas']++;
                $erro = "Usuário ou senha incorretos.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RH-News - Login</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/customlogin.css">
  <!-- Google Fonts: Roboto -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
      font-family: 'Roboto', sans-serif;
    }
    /* Centraliza o card verticalmente e adiciona um fundo sutil */
    .login-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      padding: 20px;
    }
    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
      overflow: hidden;
      max-width: 400px;
      width: 100%;
      animation: fadeInUp 0.8s ease-out;
    }
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    .card-header {
      background: linear-gradient(90deg, #295959, #2a9d8f);
      color: #fff;
      text-align: center;
      padding: 30px;
    }
    .card-header img {
      max-width: 80px;
      margin-bottom: 10px;
    }
    .card-header h3 {
      margin: 0;
      font-weight: 700;
      letter-spacing: 1px;
    }
    .card-body {
      padding: 40px 30px;
    }
    .form-control {
      border-radius: 8px;
      border: 1px solid #ced4da;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .form-control:focus {
      border-color: #2a9d8f;
      box-shadow: 0 0 0 0.2rem rgba(42, 157, 143, 0.25);
    }
    .btn-primary {
      background-color: #295959;
      border: none;
      border-radius: 8px;
      font-weight: 500;
      transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
      background-color: #244747;
    }
    a.btn-link {
      color: #295959;
      text-decoration: none;
      transition: color 0.3s ease;
    }
    a.btn-link:hover {
      color: #2a9d8f;
    }
    .alert {
      border-radius: 8px;
    }
    .text-center h6 {
      font-weight: 500;
      color: #6c757d;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="container login-container">
    <div class="card">
      <div class="card-header">
        <img src="img/logosemfundo.png" alt="Logo">
        <h3>RH-News</h3>
      </div>
      <div class="card-body">
        <?php if (isset($erro)): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        <form method="POST" action="">
          <div class="mb-3">
            <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuário" required>
          </div>
          <div class="mb-3">
            <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" required>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Login</button>
          </div>
        </form>
        <div class="text-center mt-3">
          <a href="index.php" class="btn btn-link">Voltar para página inicial</a>
        </div>
        <div class="text-center">
          <h6>@Grupogalactus</h6>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
