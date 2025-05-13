<?php
session_start();
header('Content-Type: application/json'); // <-- Isso é essencial para JS interpretar corretamente o JSON

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pincelsolto";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Erro na conexão com o banco de dados.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $area = $_POST['area'];
    $estilo = $_POST['estilo'];

    $verifica = $conn->prepare("SELECT id FROM artista WHERE email = ?");
    $verifica->bind_param("s", $email);
    $verifica->execute();
    $verifica->store_result();

    if ($verifica->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Este e-mail já está cadastrado!']);
        exit();
    }
    $verifica->close();

    $sql = "INSERT INTO artista (nome, email, senha, area, estilo) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $usuario, $email, $senha, $area, $estilo);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Cadastro realizado com sucesso!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao cadastrar: ' . $conn->error]);
    }

    $stmt->close();
}

$conn->close();
?>
