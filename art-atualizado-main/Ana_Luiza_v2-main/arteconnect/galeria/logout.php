<?php
include("conexao.php");
session_start();

$email = $_POST['email'];
$senha = $_POST['senha'];

$sql = "SELECT * FROM usuarios WHERE email = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
    
    // Verifica se a senha está correta
    if (password_verify($senha, $usuario['senha'])) {
        // Armazena informações do usuário na sessão
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['tipo_usuario'] = $usuario['tipo']; // Pode ser 'artista' ou 'comum'
        
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
<?php
include("conexao_dupla.php");
?>