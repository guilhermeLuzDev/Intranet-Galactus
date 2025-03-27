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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === "add") { // Adicionar novo comunicado
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
    } elseif ($action === "edit") { // Editar comunicado
        $id = $_POST['id'];
        $titulo = $_POST['titulo'];
        $imagem = $_FILES['imagem']['name'] ?? null;

        if (!empty($titulo)) {
            if ($imagem) {
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($imagem);

                if (move_uploaded_file($_FILES['imagem']['tmp_name'], $target_file)) {
                    $sql = "UPDATE avisos SET titulo='$titulo', imagem='$imagem' WHERE id=$id";
                }
            } else {
                $sql = "UPDATE avisos SET titulo='$titulo' WHERE id=$id";
            }

            $message = ($conn->query($sql) === TRUE) ? "Publicação atualizada com sucesso!" : "Erro ao atualizar: " . $conn->error;
        }
    } elseif ($action === "delete") { // Excluir comunicado
        $id = $_POST['id'];
        $sql = "DELETE FROM avisos WHERE id=$id";
        $message = ($conn->query($sql) === TRUE) ? "Publicação excluída com sucesso!" : "Erro ao excluir: " . $conn->error;
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
</head>

<body>
    <nav class="navbar navbar-expand-lg fixed-top" style="background-color: #295959;">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="img/logogalactus.jpg" alt="Logo" width="35" height="35" class="d-inline-block align-text-top">
            </a>
            <h2 class="text-center text-white">Grupo Galactus - News</h2>
        </div>
    </nav><br>

    <div class="container my-5">        
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" required>
            </div>
            <div class="mb-3">
                <label for="imagem" class="form-label">Imagem</label>
                <input type="file" class="form-control" id="imagem" name="imagem" required>
            </div>
            <button type="submit" class="btn btn-primary">Publicar</button>
        </form>
        <?php if (!empty($message)): ?>
            <div class="alert alert-info mt-3"><?= $message ?></div>
        <?php endif; ?>
    </div>

    <div class="container my-4">
         <div class="row row-cols-1 row-cols-md-4 g-3">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col">
                        <div class="card">
                            <img src="uploads/<?= $row['imagem'] ?>" class="card-img-top aviso-img" alt="Aviso">
                            <div class="card-body text-center">
                                <h6 class="card-title"><?= $row['titulo'] ?></h6>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $row['id'] ?>">Excluir</button>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">Editar</button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal para exclusão -->
                    <div class="modal fade" id="deleteModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Confirmação de Exclusão</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Tem certeza que deseja excluir essa publicação?
                                </div>
                                <div class="modal-footer">
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <button type="submit" class="btn btn-danger">Sim</button>
                                    </form>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal para edição -->
                    <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar comunicado</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="titulo<?= $row['id'] ?>" class="form-label">Título</label>
                                            <input type="text" class="form-control" id="titulo<?= $row['id'] ?>" name="titulo" value="<?= $row['titulo'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="imagem<?= $row['id'] ?>" class="form-label">Imagem (opcional)</label>
                                            <input type="file" class="form-control" id="imagem<?= $row['id'] ?>" name="imagem">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Salvar</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    </div>
                                </form>
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
