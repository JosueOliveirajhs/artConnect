<?php
// Configuração do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "artconnect";

// Conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica conexão
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Falha na conexão com o banco de dados!']);
    exit;
}

// Recebendo dados do formulário
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$cpf = $_POST['cpf'];
$phone = $_POST['phone'];
$cep = $_POST['cep'];
$card_name = $_POST['card_name'];
$card_number = $_POST['card_number'];
$expiry_date = $_POST['expiry_date'];
$cvv = $_POST['cvv'];
$plan = $_POST['plan'];

// Determinando o valor do plano
$amount = 0;
if ($plan === "básico") $amount = 19.90;
if ($plan === "profissional") $amount = 30.90;
if ($plan === "empresarial") $amount = 129.90;

// Inserindo dados no banco de dados
$sql = "INSERT INTO users (full_name, email, cpf, phone, cep) VALUES ('$full_name', '$email', '$cpf', '$phone', '$cep')";
if ($conn->query($sql) === TRUE) {
    $user_id = $conn->insert_id; // Obtém o ID do usuário inserido

    $sql_payment = "INSERT INTO payments (user_id, card_name, card_number, expiry_date, cvv, plan, amount) 
                    VALUES ('$user_id', '$card_name', '$card_number', '$expiry_date', '$cvv', '$plan', '$amount')";
    if ($conn->query($sql_payment) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Pagamento realizado com sucesso!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Falha ao processar o pagamento!']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Falha ao salvar os dados do usuário!']);
}

$conn->close();
?>
