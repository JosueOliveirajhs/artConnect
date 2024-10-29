<?php
header('Content-Type: application/json');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "artconnect";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Erro de conexão: " . $conn->connect_error]));
}

// Se o método for POST, salvamos uma nova mensagem no banco
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $conn->real_escape_string($_POST['usuario']);
    $mensagem = $conn->real_escape_string($_POST['mensagem']);
    
    $sql = "INSERT INTO chat_messages (usuario, mensagem) VALUES ('$usuario', '$mensagem')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
}
// Se o método for GET, buscamos todas as mensagens para exibição
else {
    $sql = "SELECT usuario, mensagem, timestamp FROM chat_messages ORDER BY timestamp DESC LIMIT 50";
    $result = $conn->query($sql);
    
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    echo json_encode($messages);
}

$conn->close();
?>
