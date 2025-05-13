<?php
session_start();
include './conexao.php';

$protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$dominio = $_SERVER['HTTP_HOST'];
$caminho = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
define('BASE_URL', $protocolo . $dominio . $caminho);

$usuario_logado = isset($_SESSION['usuario_id']) || isset($_SESSION['artista_id']) || isset($_SESSION['empresa_id']);
$usuario_id = $_SESSION['usuario_id'] ?? $_SESSION['artista_id'] ?? $_SESSION['empresa_id'] ?? null;

$artista = ['foto_perfil' => '', 'nome' => 'Usuário'];
if (isset($_SESSION['artista_id'])) {
    $stmt = $conn->prepare("SELECT nome, foto_perfil FROM artista WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['artista_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $artista = $result->fetch_assoc();
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/1c77068e3f.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="<?= BASE_URL ?>/img/logo2.png" type="image/x-icon">
    <title>ArtConnect</title>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="<?= BASE_URL ?>/Home/index.css">
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <a href="<?= BASE_URL ?>/index2.php">Home</a>
            <a href="<?= BASE_URL ?>/Galeria/exibir2.php">Galeria</a>
            <button onclick="redirectToMural()" class="calendario">Calendário</button>
            <a href="<?= BASE_URL ?>/planos/previa_pagamento.html">Planos</a>
            <a href="#aboutus">Sobre Nós</a>
            <a href="#contact">Contato</a>
            <div class="dropdown">
                <button onclick="toggleDropdown()" class="dropbtn">
                    <?php if (!empty($artista['foto_perfil'])): ?>
                        <img src="<?= $artista['foto_perfil'] ?>" alt="Foto de Perfil" width="100" height="100">
                    <?php else: ?>
                        <img src="path/to/default-photo.jpg" alt="Foto de Perfil Padrão" width="100" height="100">
                    <?php endif; ?>
                </button>
                <div id="myDropdown" class="dropdown-content">
                    <div class="user-info">
                        <?php if (!empty($artista['foto_perfil'])): ?>
                            <img src="<?= $artista['foto_perfil'] ?>" alt="Foto de Perfil" width="50" height="50">
                        <?php else: ?>
                            <img src="path/to/default-photo.jpg" alt="Foto de Perfil Padrão" width="50" height="50">
                        <?php endif; ?>
                        <h1>Nome: <?= htmlspecialchars($artista['nome']) ?></h1>
                    </div>
                    <hr>
                    <a href="./Home/perfil.php"><div class="edit-profile"><i class="fa-solid fa-user-gear"></i></div><p>Edite seu Perfil</p></a>
                    <a href="./Galeria/upload.html"><div class="upload-profile"><i class="fa-solid fa-camera-retro"></i></div><p>Poste sua Obra</p></a>
                    <a href="./eventos.php"><div class="upload-event"><i class="fa-solid fa-calendar-plus"></i></div><p>Publique seu Evento</p></a>
                    <a href=""><div class="acessibilidade-profile"><i class="fa-solid fa-universal-access"></i></div><p>Acessibilidade</p></a>
                    <a href="#contact"><div class="help-profile"><i class="fa-solid fa-circle-info"></i></div><p>Ajuda</p></a>
                    <a href="index.php"><div class="logout-profile"><i class="fa-solid fa-right-to-bracket"></i></div><p>Logout</p></a>
                </div>
            </div>
        </nav>
        <a href="" class="logo"><img src="./img/logo3.png" /></a>
    </header>

    
    <div class="container">
    <div class="left-side"></div>
    <div class="right-side">
        <h3>Bem-vindo(a), artista!</h3>
        <p>Mostre seu talento, conecte-se com admiradores e inspire o Brasil com sua arte.</p>
    </div>
</div>

    <div class="carousel">
        <p class='preview'>Gallery Preview</p>
        <div class="gallery" id="gallery"></div>
        <div class="carousel-buttons">
            <button id="prev">&lt;</button>
            <button id="next">&gt;</button>
        </div>
    </div>

    <section>
        <div class="aboutus" id="aboutus">
            <div class="left">
                <img src="<?= BASE_URL ?>/img/aboutus04.jpg">
            </div>
            <div class="right">
                <h1>Conheça nossa equipe</h1>
                <div>
                    <button onclick="toggleText()" class="bnt-about">Mostrar mais
                        <i id="icon" class="fa-solid fa-caret-down"></i></button>
                    <div id="text-container">
                        <p>Nos últimos anos, o cenário das artes independentes vem ganhando força na sociedade contemporânea, impulsionado pela democratização do acesso à informação e pelas novas plataformas digitais. Artistas de diferentes estilos e origens têm encontrado oportunidades para compartilhar suas obras, explorar novas formas de expressão e alcançar públicos diversos. Esse movimento tem sido essencial para a valorização da diversidade cultural, permitindo que vozes antes marginalizadas ganhem espaço e reconhecimento. Além disso, a crescente conexão entre arte e tecnologia tem possibilitado novas experiências imersivas, colaborativas e acessíveis, transformando a maneira como apreciamos e consumimos arte.

                        </p>
                    </div>
                </div>
                <div class="right-img">
                    <img src="<?= BASE_URL ?>/img/aboutus01.jpg" alt="">
                    <img src="<?= BASE_URL ?>/img/aboutus02.jpg" alt="">
                    <img src="<?= BASE_URL ?>/img/aboutus03.jpg" alt="">
                </div>
            </div>
        </div>
    </section>

    <section class="contact" id="contact">
        <div class="containerContato">
            <h2>Contato</h2>
            <div class="contact-wrapper">
                <div class="contact-form">
                    <h3>Nos mande uma mensagem</h3>
                    <form id="contactForm">
                        <div class="form-group">
                            <input class="contato" type="text" id="name" name="name" placeholder="Digite seu nome" required>
                        </div>
                        <div class="form-group">
                            <input class="contato" type="email" id="email" name="email" placeholder="Digite seu email" required>
                        </div>
                        <div class="form-group">
                            <textarea name="message" id="message" placeholder="Digite sua mensagem" required></textarea>
                        </div>
                        <button type="submit">Enviar mensagem</button>
                    </form>
                </div>
                <div class="contact-info">
                    <h3>Informações para Contato</h3>
                    <p><i class="fa-solid fa-phone"></i>55 (81) 91234-5678</p>
                    <p><i class="fa-solid fa-envelope"></i>artconnect.site@gmail.com</p>
                    <div class="social-media">
                        <a href="" class="fab fa-whatsapp"></a>
                        <a href="" class="fab fa-twitter"></a>
                        <a href="" class="fab fa-instagram"></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="credits">
            created by <span> &copy; 2024 ArteConnect</span> | all rights reserved.
        </div>
    </footer>

    <script>
        
function redirectToMural() {
    window.location.href = "<?= BASE_URL ?>/mural2.html";
}

const baseUrl = "<?= BASE_URL ?>";
const gallery = document.getElementById('gallery');
let artworks = [];
let currentIndex = 0;

function loadGallery() {
    fetch(`${baseUrl}/Galeria/get_artworks.php`)
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data) && data.length > 0) {
                artworks = data;
                displayArtwork(currentIndex);
            } else {
                gallery.innerHTML = '<p>Nenhuma obra encontrada.</p>';
            }
        })
        .catch(error => {
            console.error('Erro ao carregar a galeria:', error);
            gallery.innerHTML = '<p>Erro ao carregar a galeria.</p>';
        });
}

function displayArtwork(index) {
    gallery.innerHTML = '';
    for (let i = 0; i < 3; i++) {
        const current = (index + i) % artworks.length;
        const post = artworks[current];
        const postElement = document.createElement('div');
        postElement.classList.add('post');
        postElement.innerHTML = `
            <a href="${baseUrl}/Galeria/exibir.php" class="post-link">
                <p><strong>Autor:</strong> ${post.autor}</p>
                <img src="${post.imagem}" alt="Obra de arte">
                <p><strong>Descrição:</strong> ${post.descricao}</p>
            </a>
        `;
        gallery.appendChild(postElement);
    }
}

function showNextArtwork() {
    currentIndex = (currentIndex + 3) % artworks.length;
    displayArtwork(currentIndex);
}

function showPrevArtwork() {
    currentIndex = (currentIndex - 3 + artworks.length) % artworks.length;
    displayArtwork(currentIndex);
}

function toggleText() {
    document.getElementById("text-container").classList.toggle("show");
    document.getElementById("icon").classList.toggle("rotate");
}


document.getElementById('next').addEventListener('click', showNextArtwork);
document.getElementById('prev').addEventListener('click', showPrevArtwork);


window.addEventListener('DOMContentLoaded', loadGallery);



function toggleDropdown() {
    document.getElementById("myDropdown").classList.toggle("show");
}


window.onclick = function(event) {
    const dropdown = document.getElementById("myDropdown");
    const button = document.querySelector(".dropbtn");

    if (!button.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.classList.remove("show");
    }
}
    </script>
</body>
</html>