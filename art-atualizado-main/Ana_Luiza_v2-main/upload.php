<?php
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
    $target_dir = "uploads/"; // Pasta onde as imagens serão salvas
    $target_file = $target_dir . basename($_FILES["imagem"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Verifica o tipo de arquivo
    if ($imageFileType != "jpg" && $imageFileType != "png") {
        echo "Desculpe, somente arquivos JPG e PNG são permitidos.";
        exit;
    }

    // Move o arquivo para a pasta de uploads
    if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $target_file)) {
        // Salva informações no banco de dados
        $sql = "INSERT INTO imagens (nome_arquivo, caminho) VALUES ('" . basename($_FILES["imagem"]["name"]) . "', '$target_file')";
        
        if ($conn->query($sql) === TRUE) {
            echo "A imagem foi carregada e registrada com sucesso.";
        } else {
            echo "Erro ao registrar a imagem: " . $conn->error;
        }
    } else {
        echo "Desculpe, ocorreu um erro ao fazer o upload da sua imagem.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <script src="https://kit.fontawesome.com/1c77068e3f.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="./img/logo2.png" type="image/x-icon">
    <link rel="stylesheet" href="./upload.css">
    <style>
         :root {
        --azul: #191940;
        --vermelho: #060f46;
        --verde: #435863;
        --branco-gelo: #e6d0bdd2;
        --branco-creme: #f6ebe2;
        --preto: #333;
        --fundo-claro: #eee;
        --fundo-escuro: #121212;
        --texto-claro: #000;
        --texto-escuro: #fff;
    }
        *{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    border: none;
    outline: none;
    font-family: 'work sans', sans-serif;
    text-decoration: none;
    text-transform: none;
    font-style: normal;
    transition: all 0.5s ease-in;
    scroll-behavior: smooth;
    scroll-padding-top: 16vh;
}

html {
    font-size: 62.5%;
    overflow-x: hidden;
}
section{
    padding: 2rem 7%;
}

body {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: url('./img/bg-outrascoisas.jpg');
    background-size: cover;
    background-position: center;
}

body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.2); /* Cor preta com 50% de opacidade */
    z-index: 1; /* Certifica-se que o overlay fique atrás do conteúdo */
}

.container {
    position: relative;
    z-index: 2; /* Garante que o conteúdo fique acima do overlay */
}

.container{
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

.container h1{
    font-size: 3rem;
    text-align: center;
    margin-top: 3rem;
    margin-bottom: 0.5rem;
}

.container h2{
    font-size: 1.7rem;
    text-align: center;
    font-weight: lighter;
    margin-top: 3rem;
    margin-bottom: 0.5rem;
}
/* 
.input-box {
    position: relative;
    width: 100%;
    height: 50px;
    margin: 20px 0;
} */

input{
    width: 100%;
    height: 100%;
    background-color: transparent;
    border: 2px solid;
    border-radius: 40px;
    outline: none;
    font-size: 16px;
    color: #fff;
    padding: 20px 45px 20px 20px;
    margin-top: 2rem;
} 
#dark-mode-toggle {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 10px 20px;
        background-color: var(--azul);
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 5px;
}

.home-btn{
        position: fixed;
        bottom: 20px;
        right: 100px;
        padding: 10px 20px;
        font-size: 1.5rem;
        background-color: var(--azul);
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 5px;
}
.btn-up input{
    background-color: transparent;
    border: none;
}
.input-box input::placeholder{
    color: #fff;
} */
/* 
.input-box i{
    position: absolute;
    right: 20px;
    top: 80%;
    transform: translateY(-50%);
    font-size: 20px;

 } */
    </style>
    <title>ArtConnet Upload</title>
</head>
<body>
    
    <button id="dark-mode-toggle"><i class="fa-solid fa-moon"></i></button>
    <button class="home-btn" href="exibir.php">Ir para Galeria</button>
    <div class="container">
        <h1>Poste sua obra</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <h2>Selecione a imagem para upload:</h2> 
        <input   type="file" name="imagem" required>
        <input class="btn-up" type="submit" value="Upload Imagem">
    </form>
    </div>
    
</body>

</html>