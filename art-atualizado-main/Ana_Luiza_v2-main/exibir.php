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
    <style>
        /* Estilo do cabeçalho e dos ícones */
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 20px;
            background-color: #333;
        }


        .bi-moon-stars-fill{
            position: fixed;
            right: 140px;
        }
        .bi-chat-dots{
            position: fixed;
            right: 90px;
        }
        .bi-house-door{
            position: fixed;
            right: 40px;
            top: 25px;
        }
        h1 {
            font-size: 1.8rem;
            color: #fff;
            margin: 0;
        }

        /* Estilos do contêiner de ícones */
        .icon-container {
            display: flex;
            align-items: center;
            gap: 15px; /* Espaçamento entre os ícones */
        }

        .icon-container svg {
            cursor: pointer;
            width: 20px;
            height: 24px;
            fill: #fff; /* Cor dos ícones em branco */
            transition: fill 0.3s; /* Efeito de transição suave */
        }

        .icon-container svg:hover {
            fill: #bbb; /* Cor ao passar o mouse */
        }

        /* Estilo da janela de chat */
        .chat-window {
            display: none; /* Oculta a janela inicialmente */
            position: fixed;
            bottom: 70px; /* Ajuste para que a janela de chat suba um pouco */
            right: 20px;
            width: 500px;
            height: 500px;
            background-color: #f6ebe2;
            border: 2px solid #435863;
            border-radius: 10px 10px 0 0;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
        .chat-header {
            background-color: #435863;
            color: #fff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 10px 10px 0 0;
        }
        .chat-messages {
            padding: 10px;
            height: 300px;
            overflow-y: auto;
            color: #333;
        }
        .chat-input {
            display: flex;
            padding: 10px;
            border-top: 1px solid #ddd;
        }
        .chat-input input {
            flex: 1;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-right: 5px;
        }
        .chat-input button {
            padding: 8px;
            background-color: #435863;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <h1>Galeria de Artes</h1>
        <div class="icon-container">
            <!-- Ícone de Modo Escuro -->
            <svg id="darkModeBtn" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-moon-stars-fill" viewBox="0 0 16 16">
                <path d="M6 .278a.77.77 0 0 1 .08.858 7.2 7.2 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277q.792-.001 1.533-.16a.79.79 0 0 1 .81.316.73.73 0 0 1-.031.893A8.35 8.35 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.75.75 0 0 1 6 .278"/>
                <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.73 1.73 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.73 1.73 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.73 1.73 0 0 0 1.097-1.097zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z"/>
            </svg>
            <!-- Ícone de Chat -->
            <svg id="chatIcon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-chat-dots" viewBox="0 0 16 16">
                <path d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0m4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                <path d="m2.165 15.803.02-.004c1.83-.363 2.948-.842 3.468-1.105A9 9 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.4 10.4 0 0 1-.524 2.318l-.003.011a11 11 0 0 1-.244.637c-.079.186.074.394.273.362a22 22 0 0 0 .693-.125m.8-3.108a1 1 0 0 0-.287-.801C1.618 10.83 1 9.468 1 8c0-3.192 3.004-6 7-6s7 2.808 7 6-3.004 6-7 6a8 8 0 0 1-2.088-.272 1 1 0 0 0-.711.074c-.387.196-1.24.57-2.634.893a11 11 0 0 0 .398-2"/>
            </svg>
            <!-- Ícone de Casa -->
            <a href="index2.html">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-house-door" viewBox="0 0 16 16">
                    <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4z"/>
                </svg>
            </a>
        </div>
    </header>

    <section class="gallery" id="gallery"></section>

    <!-- Janela de chat -->
    <div class="chat-window" id="chatWindow">
        <div class="chat-header">
            <span>Chat</span>
            <button onclick="toggleChat()">X</button>
        </div>
        <div class="chat-messages" id="chatMessages">
            <!-- Mensagens do chat serão exibidas aqui -->
        </div>
        <div class="chat-input">
    <input type="text" id="usuarioInput" placeholder="Seu nome">
    <input type="text" id="messageInput" placeholder="Digite uma mensagem...">
    <button id="sendButton">Enviar</button>
</div>

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

        function toggleChat() {
            const chatWindow = document.getElementById('chatWindow');
            chatWindow.style.display = chatWindow.style.display === 'none' ? 'block' : 'none';
        }


        
        // Função para carregar mensagens
        function loadMessages() {
            fetch('chat.php')
                .then(response => response.json())
                .then(messages => {
                    const chatMessages = document.getElementById('chatMessages');
                    chatMessages.innerHTML = '';
                    messages.reverse().forEach(message => {
                        const messageDiv = document.createElement('div');
                        messageDiv.classList.add('chat-message');
                        messageDiv.innerHTML = `<strong>${message.usuario}</strong>: ${message.mensagem}`;
                        chatMessages.appendChild(messageDiv);
                    });
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                })
                .catch(error => console.error('Erro ao carregar mensagens:', error));
        }

        // Garante que o código execute somente após o carregamento completo do DOM
document.addEventListener('DOMContentLoaded', function() {
    // Função para enviar uma mensagem


    
    function sendMessage() {
            const usuarioInput = document.getElementById('usuarioInput');
            const messageInput = document.getElementById('messageInput');

            const usuario = usuarioInput.value.trim();
            const mensagem = messageInput.value.trim();

            if (usuario && mensagem) {
                fetch('chat.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `usuario=${encodeURIComponent(usuario)}&mensagem=${encodeURIComponent(mensagem)}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        messageInput.value = ''; // Limpa o campo de mensagem
                        loadMessages(); // Atualiza a lista de mensagens
                    } else {
                        console.error('Erro ao enviar mensagem:', data.message);
                    }
                })
                .catch(error => console.error('Erro ao enviar mensagem:', error));
            }
        }

        document.getElementById('chatIcon').addEventListener('click', toggleChat);
        document.getElementById('sendButton').addEventListener('click', sendMessage);
        
        
    // Função para carregar as mensagens do chat
    function loadMessages() {
        fetch('chat.php')
            .then(response => response.json())
            .then(messages => {
                const chatMessages = document.getElementById('chatMessages');
                chatMessages.innerHTML = '';
                messages.reverse().forEach(message => {
                    const messageDiv = document.createElement('div');
                    messageDiv.classList.add('chat-message');
                    messageDiv.innerHTML = `<strong>${message.usuario}</strong>: ${message.mensagem}`;
                    chatMessages.appendChild(messageDiv);
                });
                chatMessages.scrollTop = chatMessages.scrollHeight;
            })
            .catch(error => console.error('Erro ao carregar mensagens:', error));
    }

    // Carrega as mensagens a cada 5 segundos para atualização em tempo real
    setInterval(loadMessages, 5000);
});

        // Abre o chat ao clicar no ícone
        document.getElementById('chatIcon').addEventListener('click', toggleChat);




        // Carrega a galeria ao iniciar
        window.addEventListener('DOMContentLoaded', loadGallery);
    </script>
</body>
</html>
