<?php
// Configurações do banco de dados
$host = 'localhost';  // O endereço do servidor MySQL (geralmente 'localhost')
$db = 'ramal_db';     // O nome do banco de dados
$user = 'root'; // Seu nome de usuário do MySQL
$pass = '';   // Sua senha do MySQL
$charset = 'utf8mb4';  // O charset a ser utilizado

// DSN (Data Source Name) para a conexão PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opções PDO para melhor gerenciamento de erros
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Criando a instância PDO
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Em caso de erro na conexão, exibe uma mensagem
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}



?>
