<?php
session_start();
include 'conexao.php';  // Inclua sua conexão com o banco de dados

// Verificar se a requisição foi feita via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receber o ID do evento via JSON
    $data = json_decode(file_get_contents('php://input'), true);
    $id_evento = $data['id_evento'] ?? null;

    // Verificar se o ID do evento foi recebido
    if ($id_evento === null) {
        echo json_encode(['status' => 'error', 'message' => 'ID do evento não fornecido']);
        exit;
    }

    // Obter o ID do artista logado
    $usuario_id = $_SESSION['usuario_id'];

    // Verificar se o evento pertence ao artista logado
    $sql = "SELECT * FROM eventos WHERE id = ? AND id_artista = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('ii', $id_evento, $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar se o evento existe e pertence ao artista
        if ($result->num_rows > 0) {
            // Evento encontrado, podemos deletar
            $delete_sql = "DELETE FROM eventos WHERE id = ?";
            if ($delete_stmt = $conn->prepare($delete_sql)) {
                $delete_stmt->bind_param('i', $id_evento);
                if ($delete_stmt->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'Evento deletado com sucesso']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Erro ao deletar o evento']);
                }
                $delete_stmt->close();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Erro ao preparar consulta de exclusão']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Evento não encontrado ou não autorizado a deletar']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao preparar consulta de verificação']);
    }

    $conn->close();
}
?>
