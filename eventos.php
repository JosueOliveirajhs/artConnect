<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArtConnect Upload</title>
    <script src="https://kit.fontawesome.com/1c77068e3f.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" href="./img/logo2.png" type="image/x-icon">
    <style>
        * {
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
            background: url('./img/Fundo cadastro.jpg') no-repeat center center/cover;
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
            margin-top: 1rem;
            background: rgba(38, 148, 160, 0.41);
            border: 2px solid rgba(2, 221, 250, 0.2);
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: white;
            width: 400px;
            backdrop-filter: blur(50px);
        }

        .container h1 {
            font-size: 2rem;
        }

        input, textarea {
            width: 100%;
            margin: 10px 0;
            padding: 15px;
            border: 1px solid white;
            border-radius: 8px;
            background: transparent;
            color: white;
            font-size: 1rem;
        }

        input::placeholder, textarea::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .btn-up {
            width: 100%;
            padding: 12px;
            background: white;
            color: #333;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-up:hover {
            background: transparent;
            border: 1.5px solid white;
            color: white;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Publique Seu Evento</h1>
    <form id="form_publicar_evento">
        <input type="text" name="nome_evento" placeholder="Nome do Evento" required>
        <input type="text" name="horario" placeholder="Horários" required>
        <input type="date" name="data_evento" required>
        <input type="text" name="local_evento" placeholder="Local do Evento" required>
        <h2>Selecione a imagem:</h2>
        <input type="file" name="imagem_evento" required>
        <textarea name="descricao_evento" placeholder="Descrição do evento" required></textarea>
        <input type="url" name="link_evento" placeholder="Link do Evento (opcional)">
        <input class="btn-up" type="submit" value="Publicar Evento">
    </form>
</div>
<script>
    document.getElementById('form_publicar_evento').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('upload_eventos.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);

            try {
                const jsonData = JSON.parse(data);
                Swal.fire({
                    title: jsonData.status === 'success' ? 'Sucesso!' : 'Erro!',
                    text: jsonData.message,
                    icon: jsonData.status === 'success' ? 'success' : 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    if (jsonData.status === 'success') {
                        window.location.href = 'mural2.html';
                    }
                });
            } catch (error) {
                console.error('Erro ao interpretar JSON:', error);
                Swal.fire({
                    title: 'Erro!',
                    text: 'Ocorreu um erro ao enviar o evento.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            Swal.fire({
                title: 'Erro!',
                text: 'Ocorreu um erro ao enviar o evento.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    });
</script>
</body>
</html>