<?php
// Carrega credenciais do .env
$env = __DIR__ . '/.env';
if (!file_exists($env)) {
    die("Arquivo .env não encontrado.");
}
$vars = parse_ini_file($env);

// Lê variáveis
$host   = $vars['DB_HOST'];
$dbname = $vars['DB_NAME'];
$user   = $vars['DB_USER'];
$pass   = $vars['DB_PASS'];

try {
    // Cria conexão PDO
    $pdo = new PDO(
      "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
      $user, $pass,
      [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    error_log("Erro na conexão: " . $e->getMessage());
    die("Erro ao conectar ao banco de dados.");
}
