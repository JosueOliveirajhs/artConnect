<?php
include("conexao.php");
session_start();

$post_id = $_POST['post_id'];
$usuario_id = $_SESSION['id'];
$comentario = $_POST['comment'];

$sql = "INSERT INTO comentarios (post_id, usuario_id, comentario) VALUES (?, ?, ?)";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("iis", $post_id, $usuario_id, $comentario);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao adicionar comentário.']);
}
?>