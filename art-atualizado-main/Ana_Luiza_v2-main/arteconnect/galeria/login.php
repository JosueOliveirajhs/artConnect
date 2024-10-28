<?php
session_start();
include("conexao.php");

$usuario = $_POST['usuario'];
$senha = $_POST['senha'];

// Procurar o usuário no banco de dados
$sql = "SELECT * FROM usuarios WHERE usuario = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario_data = $result->fetch_assoc();
    
    // Verificar se a senha está correta
    if (password_verify($senha, $usuario_data['senha'])) {
        // Armazenar informações do usuário na sessão
        $_SESSION['id'] = $usuario_data['id'];
        $_SESSION['usuario'] = $usuario_data['usuario'];
        $_SESSION['tipo_usuario'] = $usuario_data['tipo_usuario'];
        
        echo json_encode(['status' => 'success', 'message' => 'Login realizado com sucesso!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Senha incorreta.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Usuário não encontrado.']);
}

$stmt->close();
$conexao->close();
?>