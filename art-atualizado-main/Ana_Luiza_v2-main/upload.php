<?php
header('Content-Type: application/json'); // Define o tipo de conteúdo como JSON

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "artconnect";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Conexão falhou: " . $conn->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["imagem"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $descricao = $conn->real_escape_string($_POST["descricao"]);

    // Verifica o tipo de arquivo
    if ($imageFileType != "jpg" && $imageFileType != "png") {
        echo json_encode(["status" => "error", "message" => "Somente arquivos JPG e PNG são permitidos."]);
        exit;
    }

    // Move o arquivo para a pasta de uploads
    if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO imagens (nome_arquivo, caminho, descricao) VALUES ('" . basename($_FILES["imagem"]["name"]) . "', '$target_file', '$descricao')";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Imagem carregada com sucesso."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Erro ao registrar a imagem: " . $conn->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Erro ao fazer o upload da imagem."]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT * FROM imagens";
    $result = $conn->query($sql);

    $imagens = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $imagens[] = [
                "imagem" => $row["caminho"],
                "descricao" => $row["descricao"]
            ];
        }
    }
    echo json_encode($imagens);
}

$conn->close();

?>
