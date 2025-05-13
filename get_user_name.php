<?php
session_start();
include 'conexao.php';
if (!isset($_GET['user_id']) || intval($_GET['user_id']) === 0) {
    echo json_encode(['status' => 'error', 'message' => 'ID do usuário inválido.']);
    exit;
}

$user_id = intval($_GET['user_id']);

$sql = "SELECT nome FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Usuário não encontrado.']);
    exit;
}

$stmt->bind_result($nome);
$stmt->fetch();
$stmt->close();

echo json_encode(['nome' => $nome]);
?>