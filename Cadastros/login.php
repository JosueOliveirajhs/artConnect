<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pincelsolto";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $userTypes = [
        'usuarios' => '../index_user.php',
        'artista' => '../index2.php',
        'empresa' => '../index2.php'
    ];

    $senha_hash = null;
    $redirectPage = null;
    $usuario_id = null;

    foreach ($userTypes as $table => $page) {
        $sql = "SELECT id, senha FROM $table WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $usuario_id = $row['id'];
            $senha_hash = $row['senha'];
            $redirectPage = $page;

            if ($table == "artista") {
                $_SESSION['artista_id'] = $usuario_id;
            }

            break;
        }

        $stmt->close();
    }

    if ($senha_hash !== null && password_verify($senha, $senha_hash)) {
        $_SESSION['usuario_id'] = $usuario_id;

        echo json_encode([
            'status' => 'success',
            'message' => 'Login realizado com sucesso!',
            'redirect' => $redirectPage
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Email ou senha incorretos.'
        ]);
    }

    exit();
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
    <title>Login ArtConnect</title>
</head>
<body>
    <main class="container">
    <div class="voltar">
            <a href="../index.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                </svg>
            </a>
        </div>
        <h1>Login ArtConnect</h1>
        <form id="form-login">
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

<style> 
.voltar {
    position: absolute;
    top: -30px;
    left: -452px;
    cursor: pointer;
    padding: 10px 15px;
    background-color: #5579a8e8;
    border-radius: 50px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    border: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.voltar:hover {
    background-color: #1a8ec4;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3); 
    transform: scale(1.1);
}

.voltar svg {
    fill: white;
    width: 20px; 
    height: 20px;
}
    *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "work sans", sans-serif;
    }
    
    body {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: url('../img/Fundo Cadastro.jpg');
    background-size: cover;
    background-position: center;
}

body::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    z-index: -1;
}

    
    .container {
        position: relative;
        z-index: 2;
    }
    
    .container{
        margin-top: 20px;
        margin-block-end: 20px;
        width: 380px;
        height: 490px;
        background: rgba(38, 148, 160, 0.41);
        border: 2px solid rgba(2, 221, 250, 0.2);
        border-radius: 10px;
        color: white;
        padding: 20px 10px;
        box-shadow: 0 0 10px rba(255, 255, 255, .2);
        backdrop-filter: blur(50px);
    }
    
    .container h1{
        font-size: 45px;
        text-align: center;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
    }
    
    .input-box {
        position: relative;
        width: 100%;
        height: 50px;
        margin: 20px 0;
    }
    
    input{
        width: 100%;
        height: 100%;
        background-color: transparent;
        border: 1.5px solid;
        border-radius: 10px;
        outline: none;
        font-size: 16px;
        color: #fff;
        padding: 20px 45px 20px 20px;
        margin-top: 1rem;
        margin-bottom: -1rem;
    }
    
    .input-box input::placeholder{
        color: #fff;
    }
    
    .input-box i{
        position: absolute;
        right: 20px;
        top: 80%;
        transform: translateY(-50%);
        font-size: 20px;
        
    }
    
    .remember-forgot{
        display: flex;
        justify-content: space-between;
        margin: -15px 0 15px;
        
    }
    
    .remember-forgot{
        margin-left: 1rem;
        margin-top: 1rem;
        height: 1rem;
    }
    
    .remember-forgot label{
        accent-color: #fff;
        margin-right: 5px;
    }
    
    h5 {
       text-decoration: none;
       color: #fff;
       margin-right: 14rem;
       margin-top: 1rem;
    }
    
    p{
        text-decoration: none;
        color: #fff;
        margin-right: 1rem;
        margin-left: 3rem;
        margin-top: 1rem;
    }
    .remember-forgot a:hover{
        text-decoration: underline;
    }
    
    a{
            color: #fff;
    }
    
    .opcoes{
        position: relative;
        z-index: 2;
    }
    
    .opcoes{
        width: 380px;
        height: 400px;
        background-color: #69a0ece8;
        border: 2px solid rgba(2, 221, 250, 0.2);
        border-radius: 30px;
        color: white;
        padding: 20px 10px;
        box-shadow: 0 0 10px rba(255, 255, 255, .2);
        backdrop-filter: blur(50px);
    }
    
    .opcoes h1{
        font-size: 45px;
        text-align: center;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
    }
    
    .login{
        width: 100%;
        height: 50px;
        background-color: #fff;
        border: none;
        border-radius: 40px;
        cursor: pointer;
        font-size: 16px;
        color: #333;
        font-weight: 600;
        margin-top: 20px;
    }
    
    
    .login:hover {
        background-color: transparent;
        border: 2px solid rgba(255, 255, 255, 0.4);
        color: #fff;
    }
    .register-link{
        font-size: 14px;
        text-align: center;
        margin: 20px 0 15px;
    }
    
    .register-link a{
        text-decoration: none;
        color: #fff;
        font-weight: 500;
    }
    
    .register-link a:hover{
        text-decoration: underline;
    }

    
    @media screen and (max-width: 480px) {
    body {
        padding: 0 10px;
    }


    p{
        margin-right: 2.5rem;
    }
    .container {
        text-align: center;
        width: 90%;
        max-width: 380px;
        height: auto;
        margin-top: 10px;
        margin-bottom: 10px;
        padding: 20px 15px;
    }

    .container h1 {
        font-size: 2rem;
        margin-top: 1rem;
    }

    .input-box {
        margin: 10px 2;
    }

    input {
        font-size: 1.2rem;
        padding: 10px 15px;
    }

    .input-box i {
        font-size: 1.6rem;
        right: 15px; 
    }
    .remember-forgot {
    display: flex;
    align-items: center;
    gap: 10px;
    top: 10px;
}

.remember-forgot h5 {
    margin-top: 2rem;
    font-size: 0.9rem;
    color: #fff;
    cursor: pointer; 
    display: flex;
    align-items: center; 
    gap: 5px; 
}

.remember-forgot input[type="checkbox"] {
    appearance: none; 
    width: 10px;
    height: 10px; 
    border: 2px solid #fff; 
    border-radius: 3px;
    background-color: transparent; 
    cursor: pointer; 
    position: relative; 
}

.mostrar h5{
    top: 10rem;
}

.remember-forgot input[type="checkbox"]:checked {
    background-color: #1a8ec4;
    border-color: #1a8ec4; 
}

/* Ícone de marca */
.remember-forgot input[type="checkbox"]:checked::after {
    content: '✓';
    color: #fff;
    font-size: 14px;
    position: absolute;
    top: 1px;
    left: 3px;
}

    .login {
        font-size: 1.4rem;
        padding: 10px;
        top: 10rem;
    }

    .voltar {
        top: -8rem; 
        left: 10px; 
        padding: 8px 12px;
        border-radius: 50%;
        width: 60px; 
        height: 50px;
    }

    .voltar svg {
        width: 20px;
        height: 20px;
    }
}

</style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.1/dist/sweetalert2.all.min.js"></script>
    <script>
         document.getElementById('mostrarSenha').addEventListener('change', function () {
    const senhaInput = document.getElementById('senha');
    senhaInput.type = this.checked ? 'text' : 'password';
});

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form-login');

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(form);

            fetch('login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '🎉 Bem-vindo ao ArtConnect!',
                        html: '<strong>Login realizado com sucesso!</strong><br>Conectando você ao mundo da arte...',
                        background: '#f5f0e1',
                        color: '#3e3e3e',
                        iconColor: '#9b59b6',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        customClass: {
                            popup: 'swal2-popup-custom',
                            title: 'swal2-title-custom'
                        }
                    }).then(() => {
                        window.location.href = data.redirect;
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops! Algo deu errado...',
                        text: data.message,
                        background: '#fff0f3',
                        color: '#800f2f',
                        iconColor: '#d62828',
                        confirmButtonColor: '#d62828',
                        confirmButtonText: 'Tentar novamente',
                        customClass: {
                            popup: 'swal2-popup-custom'
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erro na conexão',
                    text: 'Verifique sua internet ou tente mais tarde.',
                    background: '#fff3cd',
                    color: '#856404',
                    iconColor: '#e0a800',
                    confirmButtonColor: '#ffc107',
                    customClass: {
                        popup: 'swal2-popup-custom'
                    }
                });
            });
        });
    } else {
        console.error("Formulário com ID 'form-login' não encontrado.");
    }
});

    </script>
</body>
</html>