<?php
include('conexao.php');
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([]);
    exit;
}

// Busca as postagens no banco de dados
$sql = "SELECT * FROM posts ORDER BY criado_em DESC";
$result = $conexao->query($sql);

$posts = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = [
            'imagem' => $row['imagem_url'],
            'descricao' => $row['descricao'],
            'criado_em' => $row['criado_em']
        ];
    }
}

// Retorna as postagens como JSON
echo json_encode($posts);
?>