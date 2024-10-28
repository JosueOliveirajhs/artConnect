<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="galeria.css">
    <link rel="shortcut icon" href="./arteconnect/img/logo2.png" type="image/x-icon">
    <title>ArtConnect Galeria</title>
    <!-- SweetAlert2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <style>
        .icon-btn{
    margin-left: 72rem;
    margin-top: -2.3rem;
    display: flex;
    background: none;
}
    </style>
    <header>
        <h1>Galeria de Artes</h1>
        <button id="darkModeBtn" class="icon-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-moon-stars-fill" viewBox="0 0 16 16">
                <path d="M6 .278a.77.77 0 0 1 .08.858 7.2 7.2 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277q.792-.001 1.533-.16a.79.79 0 0 1 .81.316.73.73 0 0 1-.031.893A8.35 8.35 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.75.75 0 0 1 6 .278"/>
                <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.73 1.73 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.73 1.73 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.73 1.73 0 0 0 1.097-1.097zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z"/>
            </svg>
            <a href="index2.html" class="home"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door" viewBox="0 0 16 16">
            <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4z"/>
          </svg></a>
        </button>
    </header>

    <section class="gallery" id="gallery"></section>

    <script>
        const gallery = document.getElementById('gallery');

        // Função para carregar a galeria de obras
        function loadGallery() {
            fetch('upload.php')
                .then(response => response.json())
                .then(data => {
                    gallery.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(post => {
                            const postElement = document.createElement('div');
                            postElement.classList.add('post');
                            postElement.innerHTML = `
                                <img src="${post.imagem}" alt="Obra de arte">
                                <p>${post.descricao}</p>
                            `;
                            gallery.appendChild(postElement);
                        });
                    } else {
                        gallery.innerHTML = '<p>Nenhuma obra encontrada.</p>';
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar a galeria:', error);
                    gallery.innerHTML = '<p>Erro ao carregar a galeria.</p>';
                });
        }

        window.addEventListener('DOMContentLoaded', loadGallery);
    </script>
</body>
</html>
