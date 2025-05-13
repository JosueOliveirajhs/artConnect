<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
include '../conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
    } else {
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'] ?? null;
    }

    if (!$id) {
        echo json_encode(["success" => false, "message" => "ID do post não fornecido."]);
        exit;
    }
    session_start();
    $usuario_logado = $_SESSION['usuario_id'] ?? null;

    $sql = "SELECT artista_id FROM imagens WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $post = $resultado->fetch_assoc();

    if (!$post) {
        echo json_encode(["success" => false, "message" => "Post não encontrado."]);
        exit;
    }

    if ($post['artista_id'] != $usuario_logado) {
        echo json_encode(["success" => false, "message" => "Você não tem permissão para excluir este post."]);
        exit;
    }
    $sql = "DELETE FROM imagens WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Post excluído com sucesso."]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao excluir post."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método não permitido."]);
}

$conn->close();
?>
