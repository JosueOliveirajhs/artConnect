<?php
session_start();
include '../conexao.php';

$usuario_logado = $_SESSION['usuario_id'] ?? null;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./arteconnect/img/logo2.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Galeria/exibir.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>ArtConnect Exibições</title>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</head>
<body>

<header class="header">
    <nav class="navbar">
    <button onclick="goToHome()"><a href="../index.php" class="links">Home</a></button>
    <button onclick="openCalendar()">Calendário</button>
    <button onclick="showPlans()">Planos</button>
    <button onclick="aboutUs()"><a href="../index.php" class="links">Sobre Nós</a></button>
    <button onclick="contactUs()"><a href="../index.php" class="links">Contato</a></button>
</nav>
<div class="logo">
    <a href="" class="logo"><img src="../img/logo3.png" /></a>
    </div>
</header>

</section>

    <section class="gallery" id="gallery"></section>

    <footer class="footer">
        <div class="credits">
            <p>created by <span> &copy; 2024 ArteConnect</span> | all rights reserved.</p>
        </div>
    </footer>

    <script>
        const gallery = document.getElementById("gallery");
const usuarioLogado = <?= json_encode($usuario_logado) ?>;

function loadGallery() {
    fetch("./upload.php")
        .then(response => response.json())
        .then(data => {
            gallery.innerHTML = "";
            console.log("Usuário logado:", usuarioLogado);

            if (data.length > 0) {
                data.forEach(post => {
                    console.log("ID do artista:", post.artista_id);

                    const postElement = document.createElement("div");
                    postElement.classList.add("post");

                    postElement.innerHTML = `
                    <p class="post-author"><strong>Autor:</strong> ${post.autor}</p>
                            ${post.artista_id == usuarioLogado ? `
                                <button class="delete-btn" onclick="confirmDelete(${post.id})">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            ` : ""}
                        <img src="${post.imagem}" alt="Obra de arte" class="post-image">
                        <p class="post-description"><strong>Descrição:</strong> ${post.descricao}</p>
                    `;

                    gallery.appendChild(postElement);
                });
            } else {
                gallery.innerHTML = "<p>Nenhuma obra encontrada.</p>";
            }
        })
        .catch(error => {
            console.error("Erro ao carregar a galeria:", error);
            gallery.innerHTML = "<p>Erro ao carregar a galeria.</p>";
        });
}

function confirmDelete(postId) {
    Swal.fire({
        title: "Tem certeza?",
        text: "Esta ação não pode ser desfeita!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        cancelButtonText: "Cancelar",
        confirmButtonText: "Sim, excluir!"
    }).then((result) => {
        if (result.isConfirmed) {
            deletePost(postId);
        }
    });
}

function deletePost(postId) {
    fetch(`./deletePost.php?id=${postId}`, { method: "DELETE" })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                Swal.fire("Excluído!", "O post foi removido com sucesso.", "success");
                loadGallery();
            } else {
                Swal.fire("Erro!", "Não foi possível excluir o post.", "error");
            }
        })
        .catch(error => {
            console.error("Erro ao excluir o post:", error);
            Swal.fire("Erro!", "Ocorreu um erro ao excluir o post.", "error");
        });
}

window.addEventListener('DOMContentLoaded', loadGallery);

function openCalendar() {
    window.location.href = "../mural.html"; // Redireciona para a página do calendário
}
function showPlans() {
    window.location.href = "../planos/previa_pagamento.html"; // Redireciona para a página de planos
}
    </script>
</body>
</html>