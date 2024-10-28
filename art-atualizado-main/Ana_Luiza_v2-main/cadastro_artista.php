<?php
// Inicia a sessão
session_start();

// Conexão com o banco de dados
$servername = "localhost"; // Altere se necessário
$username = "root"; // Altere se necessário
$password = ""; // Altere se necessário
$dbname = "artconnect"; // Altere para o nome do seu banco de dados

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografa a senha
    $area = $_POST['area'];
    $estilo = $_POST['estilo'];
    // $tipo_usuario = $_POST['tipo_usuario'];

    // Insere o artista no banco de dados
    $sql = "INSERT INTO artista (nome, email, senha, area, estilo) VALUES (?, ?, ?, ?, ? )";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $usuario, $email, $senha, $area, $estilo);

    if ($stmt->execute()) {
        header("Location: index2.html");
    } else {
        $response = array('status' => 'error', 'message' => 'Erro ao cadastrar: ' . $conn->error);
    }
    $stmt->close();
    echo json_encode($response); // Retorna a resposta como JSON
}

$conn->close();
?>