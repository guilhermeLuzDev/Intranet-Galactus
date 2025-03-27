<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
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

// Conexão com o banco de dados
$conn = new PDO("mysql:host=localhost;dbname=ramal_db", "root", "");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("ID inválido.");
}

// Exclui o contato
try {
    $stmt = $conn->prepare("DELETE FROM ramal WHERE id = :id");
    $stmt->execute([':id' => $id]);
    header("Location: agenda.php?success=1");
    exit();
} catch (PDOException $e) {
    die("Erro ao excluir o ramal: " . $e->getMessage());
}
?>