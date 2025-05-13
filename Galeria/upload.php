<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

include '../conexao.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["status" => "error", "message" => "Usuário não autenticado. Faça login para continuar."]);
    exit;
}
$artista_id = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $target_dir = "uploads/";

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (!isset($_FILES["imagem"]) || $_FILES["imagem"]["error"] != 0) {
        echo json_encode(["status" => "error", "message" => "Erro ao enviar a imagem. Código: " . $_FILES["imagem"]["error"]]);
        exit;
    }

    $nomeArquivo = basename($_FILES["imagem"]["name"]);
    $imageFileType = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));

    if (!in_array($imageFileType, ["jpg", "jpeg", "png"])) {
        echo json_encode(["status" => "error", "message" => "Somente arquivos JPG, JPEG e PNG são permitidos."]);
        exit;
    }

    $target_file = $target_dir . uniqid() . "." . $imageFileType; // Garante nomes únicos
    $descricao = isset($_POST["descricao"]) ? trim($_POST["descricao"]) : '';

    if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO imagens (nome_arquivo, caminho, descricao, artista_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nomeArquivo, $target_file, $descricao, $artista_id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Imagem carregada com sucesso."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Erro ao registrar a imagem no banco: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Erro ao mover o arquivo para a pasta de uploads."]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT i.id, i.caminho, i.descricao, a.nome AS autor, i.artista_id 
            FROM imagens i 
            JOIN artista a ON i.artista_id = a.id";
    
    $result = $conn->query($sql);
    $imagens = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $imagens[] = [
                "id" => $row["id"],
                "imagem" => $row["caminho"],
                "descricao" => $row["descricao"],
                "autor" => $row["autor"],
                "artista_id" => $row["artista_id"]
            ];
        }
    }
    echo json_encode($imagens);
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    parse_str(file_get_contents("php://input"), $_DELETE);
    
    if (!isset($_DELETE['id'])) {
        echo json_encode(["status" => "error", "message" => "ID da imagem não informado."]);
        exit;
    }

    $id = $_DELETE['id'];
    $sql = "SELECT caminho FROM imagens WHERE id = ? AND artista_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $artista_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($caminho);
        $stmt->fetch();
        $stmt->close();

        if (file_exists($caminho)) {
            unlink($caminho);
        }

        $sql = "DELETE FROM imagens WHERE id = ? AND artista_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id, $artista_id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Imagem excluída com sucesso."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Erro ao excluir a imagem."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Imagem não encontrada ou você não tem permissão para excluí-la."]);
    }
}

$conn->close();
?>
