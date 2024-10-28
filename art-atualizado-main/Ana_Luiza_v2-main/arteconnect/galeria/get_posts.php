<?php
include("conexao.php");

// Busca os posts com a contagem de curtidas e os comentários associados
$sql = "
    SELECT p.*, 
           COUNT(c.id) AS curtidas 
    FROM posts p 
    LEFT JOIN curtidas c ON p.id = c.post_id 
    GROUP BY p.id 
    ORDER BY p.criado_em DESC";
$result = $conexao->query($sql);

$posts = [];

while ($row = $result->fetch_assoc()) {
    // Para cada post, busca os comentários
    $post_id = $row['id'];
    $comentarios_sql = "SELECT * FROM comentarios WHERE post_id = ?";
    $comentarios_stmt = $conexao->prepare($comentarios_sql);
    $comentarios_stmt->bind_param("i", $post_id);
    $comentarios_stmt->execute();
    $comentarios_result = $comentarios_stmt->get_result();
    
    $comentarios = [];
    while ($comentario = $comentarios_result->fetch_assoc()) {
        $comentarios[] = $comentario;
    }
    
    // Adiciona o post com os comentários e a quantidade de curtidas
    $row['comentarios'] = $comentarios;
    $posts[] = $row;
}

echo json_encode($posts);
?>