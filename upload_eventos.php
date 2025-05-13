<?php
session_start();
include 'conexao.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome_evento = $_POST['nome_evento'];
    $horario = $_POST['horario'];
    $data_evento = $_POST['data_evento'];
    $local_evento = $_POST['local_evento'];
    $descricao_evento = $_POST['descricao_evento'];
    $link_evento = $_POST['link_evento'] ?? null;
    $id_artista = $_SESSION['usuario_id']; 

    if (isset($_FILES['imagem_evento']) && $_FILES['imagem_evento']['error'] === 0) {
 
        $imagem_nome = $_FILES['imagem_evento']['name'];
        $imagem_temp = $_FILES['imagem_evento']['tmp_name'];

        $diretorio_destino = 'uploads/';
        $imagem_destino = $diretorio_destino . basename($imagem_nome);


        if (move_uploaded_file($imagem_temp, $imagem_destino)) {
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao mover a imagem!']);
            exit;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro no envio da imagem!']);
        exit;
    }

    $sql = "INSERT INTO eventos (nome_evento, horario, data_evento, local_evento, descricao_evento, imagem_evento, link_evento, id_artista) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('ssssssss', $nome_evento, $horario, $data_evento, $local_evento, $descricao_evento, $imagem_destino, $link_evento, $id_artista);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Evento cadastrado com sucesso!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao cadastrar evento.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro na preparação da consulta: ' . $conn->error]);
    }

    $conn->close();
}
?>
