<?php
header('Content-Type: application/json');
define('BASE_URL', 'http://localhost/pincelSolto');
include '../conexao.php';

try {
    $sql = "
        SELECT 
            i.descricao, 
            i.caminho, 
            a.nome AS autor
        FROM imagens i
        INNER JOIN artista a ON i.artista_id = a.id
        ORDER BY i.id DESC 
        LIMIT 10
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $obras = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($obras as &$obra) {
        $obra['imagem'] = BASE_URL . '/Galeria/' . $obra['caminho'];
    }

    echo json_encode($obras);
} catch (PDOException $e) {
    echo json_encode(['erro' => 'Erro ao buscar dados: ' . $e->getMessage()]);
}
