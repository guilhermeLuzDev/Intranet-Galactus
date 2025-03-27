<?php
// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "galactus_news";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Variável para mensagem
$message = "";

// Processar o formulário de upload
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = isset($_POST['titulo']) ? $_POST['titulo'] : '';
    $imagem = isset($_FILES['imagem']['name']) ? $_FILES['imagem']['name'] : '';

    if (!empty($titulo) && !empty($imagem)) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($imagem);

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $target_file)) {
            $sql = "INSERT INTO avisos (titulo, imagem) VALUES ('$titulo', '$imagem')";
            $message = ($conn->query($sql) === TRUE) ? "Publicação adicionada com sucesso!" : "Erro ao salvar: " . $conn->error;
        } else {
            $message = "Erro ao mover o arquivo.";
        }
    } else {
        $message = "Preencha todos os campos.";
    }
}

$sql = "SELECT * FROM avisos ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galactus - News</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/customavisos.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top" style="background-color: #295959;">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="img/logogalactus.jpg" alt="Logo" width="35" height="35" class="d-inline-block align-text-top">
            </a>
            <h2 class="text-center">Comunicados e avisos.</h2>
            <div class="ms-auto">
                <a href="index.php" class="btn btn-light">Voltar</a>
            </div>
        </div>
    </nav>

    <!-- Galeria -->
    <div class="container my-4">
        <h3 class="text-center text-primary mb-4">Avisos</h3>
        <div class="row row-cols-1 row-cols-md-4 g-3">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col">
                        <div class="card">
                            <img src="uploads/<?= $row['imagem'] ?>" class="card-img-top aviso-img" alt="Aviso" data-bs-toggle="modal" data-bs-target="#avisosModal<?= $row['id'] ?>">
                            <div class="card-body text-center">
                                <h6 class="card-title"><?= $row['titulo'] ?></h6>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="avisosModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><?= $row['titulo'] ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <img src="uploads/<?= $row['imagem'] ?>" class="img-fluid" alt="Aviso">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">Nenhum aviso encontrado.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>
