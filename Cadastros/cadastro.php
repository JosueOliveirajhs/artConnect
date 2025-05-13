<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $conn = new mysqli("localhost", "root", "", "pincelsolto");
    if ($conn->connect_error) {
        echo json_encode(['status' => 'error', 'message' => 'Erro na conexão com o banco de dados.']);
        exit();
    }

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $verifica = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $verifica->bind_param("s", $email);
    $verifica->execute();
    $verifica->store_result();

    if ($verifica->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Este e-mail já está cadastrado!']);
        exit();
    }
    $verifica->close();

    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nome, $email, $senha);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Cadastro realizado com sucesso!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao cadastrar: ' . $conn->error]);
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuário</title>
</head>
<body>
<style>      

.voltar {
    position: absolute;
    top: -15px;
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
        height: 520px;
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
    
    .remember-forgot {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            margin-top: 10px;
        }

        .remember-forgot input[type="checkbox"] {
            width: 15px;
            height: 18px;
            margin-right: 15px;
            accent-color: #fff;
        }

        .remember-forgot label,
        .remember-forgot h5 {
            color: #fff;
            font-size: 12px;
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

    .container {
        width: 100%;
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
        font-size: 1.4rem;
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

.remember-forgot label {
    font-size: 1.2rem;
    color: #fff;
    cursor: pointer; 
    display: flex;
    align-items: center; 
    gap: 5px; 
}

.remember-forgot input[type="checkbox"] {
    appearance: none;
    width: 18px;
    height: 18px;
    border: 2px solid #fff;
    border-radius: 3px;
    background-color: transparent;
    cursor: pointer; 
    position: relative; 
}


.remember-forgot input[type="checkbox"]:checked {
    background-color: #1a8ec4;
    border-color: #1a8ec4;
}


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
        top: -190px;
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
    <div class="container">
    <div class="voltar">
            <a href="opcoes.html">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                </svg>
            </a>
        </div>
        <h1>Cadastro de Usuário</h1>
        <form id="form-usuario">
            <div class="input-box">
                <input type="text" name="nome" placeholder="Nome" required>
                <i class="bx bxs-user"></i>
            </div>
            <div class="input-box">
                <input type="email" name="email" placeholder="Email" required>
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
                <h5>Mostrar Senha</h5>
            </div>
            <button type="submit" class="login">Cadastrar</button>
        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.getElementById('mostrarSenha').addEventListener('change', function () {
        const senhaInput = document.getElementById('senha');
        senhaInput.type = this.checked ? 'text' : 'password';
    });

    document.getElementById('form-usuario').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    title: 'Bem-vindo(a) ao ArtConnect!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'Ir para o site'
                }).then(() => {
                    window.location.href = '../Cadastros/login.php';
                });
            } else {
                Swal.fire({
                    title: '⚠️ Atenção',
                    text: data.message,
                    icon: 'warning'
                });
            }
        })
        .catch(error => {
            console.error('Erro na requisição:', error);
            Swal.fire({
                title: 'Erro inesperado',
                text: 'Tente novamente mais tarde.',
                icon: 'error'
            });
        });
    });
    </script>

</body>
</html>
