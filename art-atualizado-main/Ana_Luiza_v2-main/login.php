<?php
// Inicia a sessão
session_start();

// Conexão com o banco de dados
$servername = "localhost"; // Altere se necessário
$username = "root"; // Altere se necessário
$password = ""; // Altere se necessário
$dbname = "artconnect"; // Altere para o nome do seu banco de dados

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    // Verifica nas diferentes tabelas se o usuário existe
    $tables = ['usuarios', 'artista', 'usuario_comun', 'empresa'];
    $senha_hash = null;

    foreach ($tables as $table) {
        $sql = "SELECT senha FROM $table WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // Se o usuário for encontrado, pega a senha
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($senha_hash);
            $stmt->fetch();
            break; // Sai do loop se encontrar o usuário
        }
        $stmt->close();
    }

    // Verifica se a senha está correta
    if ($senha_hash !== null && password_verify($senha, $senha_hash)) {
        // Armazena informações do usuário na sessão
        $_SESSION['email'] = $email; // Armazena o email do usuário
        echo json_encode(['status' => 'success', 'message' => 'Login realizado com sucesso!', 'tipo_usuario' => 'comum']); // Exemplo de resposta
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Email ou senha incorretos.']);
        exit();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./img/logo2.png" type="image/x-icon">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.1/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
    <title>Login ArtConnect</title>
</head>
<body>
    <main class="container">
        <h1>Login ArtConnect</h1>
        <form id="loginForm">
            <div class="input-box">
                <input type="email" id="email" name="email" placeholder="Email" required>
                <i class="bx bxs-user"></i>
            </div>
            <div class="input-box">
                <input type="password" id="senha" name="senha" placeholder="Senha" required>
                <i class="bx bxs-lock-alt"></i>
            </div>
            <div class="remember-forgot">
                <label>
                    <input type="checkbox" id="mostrarSenha"> 
                </label>
                <h5 class="mostrar">Mostrar Senha</h5>
            </div>
            <button type="submit" class="login">Entrar</button>
            <p>Não tem uma conta? <a href="opcoes.html">Cadastre-se</a></p>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.1/dist/sweetalert2.all.min.js"></script>
    <script>
        document.getElementById('mostrarSenha').addEventListener('change', function () {
            const senhaInput = document.getElementById('senha');
            senhaInput.type = this.checked ? 'text' : 'password';
        });

        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Previne o envio do formulário

            const email = document.getElementById('email').value;
            const senha = document.getElementById('senha').value;

            fetch('login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `email=${encodeURIComponent(email)}&senha=${encodeURIComponent(senha)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire("Bom trabalho!", data.message, "success").then(() => {
                        window.location.href = "index2.html"; // Altere conforme necessário
                    });
                } else {
                    Swal.fire("Erro!", data.message, "error");
                }
            })
            .catch(error => {
                console.log(error);
                Swal.fire("Erro!", "Ocorreu um erro ao logar.", "error");
            });
        });
    </script>
</body>
</html>
