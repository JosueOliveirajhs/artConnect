<?php
include("conexao.php");
session_start();

$post_id = $_POST['post_id'];
$usuario_id = $_SESSION['id'];

// Verifica se o usuário já curtiu o post
$sql = "SELECT * FROM curtidas WHERE post_id = ? AND usuario_id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $post_id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Se não curtiu, insere a curtida
    $sql = "INSERT INTO curtidas (post_id, usuario_id) VALUES (?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ii", $post_id, $usuario_id);
    $stmt->execute();
}

echo json_encode(['status' => 'success']);
?>