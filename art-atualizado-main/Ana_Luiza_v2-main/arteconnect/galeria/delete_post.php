<?php
include('conexao.php'); // Conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = $_POST['post_id'];

    // Verifica se o ID do post foi enviado
    if (!empty($postId)) {
        // Prepara a consulta para deletar o post
        $stmt = $conexao->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param('i', $postId);
        
        if ($stmt->execute()) {
            // Retorna uma resposta de sucesso
            echo json_encode(['status' => 'success', 'message' => 'Post excluído com sucesso']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao excluir o post']);
        }
        
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID do post inválido']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de requisição inválido']);
}

$conexao->close();
?>