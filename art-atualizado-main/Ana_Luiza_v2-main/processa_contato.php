<?php
// Conectar ao banco de dados
$servername = "localhost";  // Servidor MySQL
$username = "root";  // Nome de usuário MySQL
$password = "";  // Senha MySQL (deixe vazia no XAMPP se não houver senha)
$dbname = "artconnect";  // Nome do banco de dados

// Criar a conexão
$conexao = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conexao->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Erro ao conectar ao banco de dados: ' . $conexao->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['name'];
    $email = $_POST['email'];
    $mensagem = $_POST['message'];

    // Verificar se todos os campos estão preenchidos
    if (!empty($nome) && !empty($email) && !empty($mensagem)) {
        // Preparar a consulta SQL para inserir os dados
        $stmt = $conexao->prepare("INSERT INTO mensagens_contato (nome, email, mensagem) VALUES (?, ?, ?)");
        if ($stmt === false) {
            die(json_encode(['status' => 'error', 'message' => 'Erro na preparação da consulta: ' . $conexao->error]));
        }

        // Associar parâmetros e executar a consulta
        $stmt->bind_param("sss", $nome, $email, $mensagem);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Mensagem enviada com sucesso!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao enviar a mensagem: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Preencha todos os campos.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de requisição inválido.']);
}

$conexao->close();
?>