<?php
$servername = "localhost"; // Altere se necessário
$username = "root"; // Altere se necessário
$password = ""; // Altere se necessário
$dbname = "artconnect"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Recupera as imagens do banco de dados
$sql = "SELECT nome_arquivo, caminho FROM imagens";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<script src="https://kit.fontawesome.com/1c77068e3f.js" crossorigin="anonymous"></script>
<link rel="shortcut icon" href="./img/logo2.png" type="image/x-icon">
<head>
    <meta charset="UTF-8">
    <title>Exibir Imagens</title>
    <!-- <link rel="stylesheet" href="galeria.css"> -->
    <style> :root {
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
       body {
    font-family: 'Roboto', sans-serif;
    background-color: #e6d0bdd2;
    color: #333;
    margin: 0;
    padding: 20px; /* Adicionando um padding ao corpo para espaçamento */
}

.text {
    font-size: 2rem;
    letter-spacing: 1px;
}

.gallery {
    display: flex; /* Habilita o Flexbox */
    flex-wrap: wrap; /* Permite que os itens se movam para a linha seguinte, se necessário */
    justify-content: center; /* Centraliza os itens na linha */
}

.gallery div {
    margin: 10px; /* Espaçamento entre as imagens */
    text-align: center; /* Centraliza o texto abaixo das imagens */
}

img {
    max-width: 200px; /* Define a largura máxima das imagens */
    height: auto; /* Mantém a proporção da imagem */
}

a {
    color: white;
    text-decoration: none;
    font-size: 1rem;
    position: absolute;
    right: 20px;
    /* top: 30px; */
}
.home-btn{
        position: fixed;
        bottom: 20px;
        right: 100px;
        padding: 10px 20px;
        background-color: var(--azul);
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 5px;
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

    </style>
</head>
<body>
<button id="dark-mode-toggle"><i class="fa-solid fa-moon"></i></button>
<a href="index2.html" class="home-btn"><i class="fa-solid fa-house"></i></a>
    <h1 class="text">Galeria ArtConnect</h1>
    <div class="gallery"> <!-- Adicionando a classe gallery -->
        <?php
        if ($result->num_rows > 0) {
            // Saída de cada imagem
            while ($row = $result->fetch_assoc()) {
                echo "<div>";
                echo "<h3>" . htmlspecialchars($row['nome_arquivo']) . "</h3>";
                echo "<img src='" . htmlspecialchars($row['caminho']) . "' alt='" . htmlspecialchars($row['nome_arquivo']) . "' />";
                echo "</div>";
            }
        } else {
            echo "Nenhuma imagem encontrada.";
        }
        ?>
    </div>
</body>

</html>