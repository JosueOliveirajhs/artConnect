<?php
$servername = "localhost";
$username = "root";  // Ajuste conforme seu ambiente
$password = "";  // Ajuste conforme seu ambiente
$dbname = "artconnect";

// Criar a conexão
$conexao = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conexao->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Falha na conexão com o banco de dados: ' . $conexao->connect_error]));
}
?>