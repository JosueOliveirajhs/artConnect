document.addEventListener('DOMContentLoaded', () => {
    const gallery = document.getElementById('gallery');

    // Função para carregar publicações da galeria
    async function loadPosts() {
        try {
            const response = await fetch('get_posts.php'); // Busca os posts e seus dados relacionados
            const posts = await response.json();
            gallery.innerHTML = ''; // Limpa a galeria antes de exibir novas publicações

            posts.forEach(post => {
                addPostToGallery(post);
            });
        } catch (error) {
            console.error('Erro ao carregar publicações:', error);
        }
    }

    // Função para adicionar uma publicação na galeria
    function addPostToGallery(post) {
        const postDiv = document.createElement('div');
        postDiv.classList.add('post');
        postDiv.innerHTML = `
            <img src="${post.imagem_url}" alt="Obra de Arte">
            <p>${post.descricao}</p>
            <button class="like-btn" data-id="${post.id}"> ${post.curtidas} curtidas</button>
            <div class="comment-section">
                <h3>Comentários (${post.comentarios.length}):</h3>
                <ul>
                    ${post.comentarios.map(comentario => `<li>${comentario.comentario}</li>`).join('')}
                </ul>
                <textarea class="comment-input" placeholder="Adicione um comentário"></textarea>
                <button class="comment-btn" data-id="${post.id}">Comentar</button>
            </div>
        `;

        gallery.appendChild(postDiv);

        // Adiciona funcionalidade de curtir
        postDiv.querySelector('.like-btn').addEventListener('click', () => {
            likePost(post.id);
        });

        // Adiciona funcionalidade de comentar
        postDiv.querySelector('.comment-btn').addEventListener('click', () => {
            const commentInput = postDiv.querySelector('.comment-input').value;
            commentOnPost(post.id, commentInput);
        });
    }

    // Função para curtir uma publicação
    async function likePost(postId) {
        try {
            await fetch('like.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `post_id=${postId}`
            });
            loadPosts(); // Recarrega as publicações após curtir
        } catch (error) {
            console.error('Erro ao curtir publicação:', error);
        }
    }

    // Função para comentar em uma publicação
    async function commentOnPost(postId, comment) {
        if (comment.trim() !== "") {
            try {
                await fetch('comment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `post_id=${postId}&comment=${encodeURIComponent(comment)}`
                });
                loadPosts(); // Recarrega as publicações após comentar
            } catch (error) {
                console.error('Erro ao comentar na publicação:', error);
            }
        }
    }

    // Carregar as publicações ao iniciar a página
    loadPosts();
});
