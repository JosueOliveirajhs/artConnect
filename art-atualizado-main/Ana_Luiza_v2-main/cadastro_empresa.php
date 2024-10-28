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
    $nome_empresa= $_POST['nome_empresa'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografa a senha
    $cnpj = $_POST['cnpj'];
    $fone = $_POST['fone'];
    // $tipo_usuario = $_POST['tipo_usuario'];

    // Insere o artista no banco de dados
    $sql = "INSERT INTO empresa (nome_empresa, email, senha, cnpj, fone) VALUES (?, ?, ?, ?, ? )";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nome_empresa, $email, $senha, $cnpj, $fone);

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