<?php
session_start();
header('Content-Type: application/json');
include 'conexao.php';

$eventos = [];
$usuario_id = $_SESSION['usuario_id'] ?? null;

$sql = "SELECT * FROM eventos ORDER BY data_publicacao DESC";
$result = $conn->query($sql);

if ($result === false) {
    echo json_encode(['status' => 'error', 'message' => 'Erro na consulta ao banco de dados']);
    exit;
}

while ($row = $result->fetch_assoc()) {
    $row['pode_deletar'] = ($usuario_id && $row['id_artista'] == $usuario_id);
    $eventos[] = $row;
}

if (empty($eventos)) {
    echo json_encode(['status' => 'error', 'message' => 'Nenhum evento encontrado']);
    exit;
}

echo json_encode([
    'status' => 'success',
    'eventos' => $eventos
]);

exit;
?>