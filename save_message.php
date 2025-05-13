<?php
session_start();
include 'conexao.php';

// Verifica se o usuário está logado e se o ID da obra foi fornecido
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Usuário não autenticado.']);
    exit;
}

if (!isset($_POST['obra_id']) || intval($_POST['obra_id']) === 0 || !isset($_POST['conteudo'])) {
    echo json_encode(['status' => 'error', 'message' => 'Informações incompletas.']);
    exit;
}

$obra_id = intval($_POST['obra_id']);
$usuario_id = $_SESSION['usuario_id'];
$conteudo = $_POST['conteudo'];

// Verifica se a obra com esse ID existe no banco de dados
$sql = "SELECT id, nome_arquivo, autor FROM imagens WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $obra_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Obra não encontrada.']);
    exit;
}

$stmt->bind_result($obra_id, $nome_arquivo, $autor);
$stmt->fetch();
$stmt->close();

// Verificar se o autor da obra existe na tabela de artistas
$sql_artista = "SELECT id FROM artista WHERE nome = ?";
$stmt_artista = $conn->prepare($sql_artista);
$stmt_artista->bind_param("s", $autor);
$stmt_artista->execute();
$stmt_artista->store_result();

if ($stmt_artista->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Artista não encontrado.']);
    exit;
}

$stmt_artista->bind_result($artista_id);
$stmt_artista->fetch();
$stmt_artista->close();

// Agora, salva a mensagem no banco de dados
$sql_mensagem = "INSERT INTO mensagens (obra_id, remetente_id, destinatario_id, conteudo) VALUES (?, ?, ?, ?)";
$stmt_mensagem = $conn->prepare($sql_mensagem);
$stmt_mensagem->bind_param("iiis", $obra_id, $usuario_id, $artista_id, $conteudo);
$stmt_mensagem->execute();
$stmt_mensagem->close();

echo json_encode(['status' => 'success', 'message' => 'Mensagem enviada com sucesso.']);
?>
