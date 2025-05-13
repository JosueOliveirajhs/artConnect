<?php 
session_start();
require '../conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$usuario_id = filter_var($_SESSION['usuario_id'], FILTER_VALIDATE_INT);

if (!$usuario_id) {
    echo "Sessão inválida. Faça login novamente.";
    session_destroy();
    exit();
}

$query = "SELECT nome, email, area, estilo, foto_perfil FROM artista WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$artista = $result->fetch_assoc();

if (!$artista) {
    echo "Artista não encontrado.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = htmlspecialchars($_POST['nome']);
    $area = htmlspecialchars($_POST['area']);
    $estilo = htmlspecialchars($_POST['estilo']);
    $foto_perfil = $artista['foto_perfil'];

    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        $foto_nome = $_FILES['foto_perfil']['name'];
        $foto_temp = $_FILES['foto_perfil']['tmp_name'];
        $foto_ext = pathinfo($foto_nome, PATHINFO_EXTENSION);
        $foto_destino = 'uploads/' . uniqid() . '.' . $foto_ext;

        if (move_uploaded_file($foto_temp, $foto_destino)) {
            $foto_perfil = $foto_destino;
        }
    }

    $update_query = "UPDATE artista SET nome = ?, area = ?, estilo = ?, foto_perfil = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param('ssssi', $nome, $area, $estilo, $foto_perfil, $usuario_id);

    if ($update_stmt->execute()) {
        header('Location: perfil.php?sucesso=1');
        exit();
    } else {
        $erro = "Erro ao atualizar os dados. Tente novamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Artista</title>
</head>
<body>
    <div class="container">
        <h1 class="title">Perfil do Artista</h1>
        <?php if (isset($erro)): ?>
            <p class="error"><?= $erro ?></p>
        <?php endif; ?>
        <?php if (isset($_GET['sucesso'])): ?>
            <p class="success">Dados atualizados com sucesso!</p>
        <?php endif; ?>
        <form action="perfil.php" method="POST" enctype="multipart/form-data">
            <div class="profile-card">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($artista['nome']) ?>" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($artista['email']) ?>" readonly>
                
                <label for="area">Área:</label>
                <input type="text" id="area" name="area" value="<?= htmlspecialchars($artista['area']) ?>" required>
                
                <label for="estilo">Estilo:</label>
                <input type="text" id="estilo" name="estilo" value="<?= htmlspecialchars($artista['estilo']) ?>" required>
                <div class="profile-photo">
                    <?php if ($artista['foto_perfil']): ?>
                        <img src="<?= $artista['foto_perfil'] ?>" alt="Foto de Perfil" width="100" height="100">
                    <?php else: ?>
                        <p>Nenhuma foto de perfil definida.</p>
                    <?php endif; ?>
                </div>
                <label for="foto_perfil">Alterar Foto de Perfil:</label>
                <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*">
            </div>
            <button class="edit-button" type="submit">Salvar Alterações</button>
        </form>
    </div>
</body>
</html>

<style>
:root {
    --azul: #191940;
    --vermelho: #060f46;
    --verde: #435863;
    --branco-gelo: #e6d0bdd2;
    --branco-creme: #f6ebe2;
    --preto: #333;
}
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Work Sans", sans-serif;
}

body {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: url('../img/Fundo Cadastro.jpg') no-repeat center center;
    background-size: cover;
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
    max-width: 500px;
    background: rgba(38, 148, 160, 0.41);
    border: 2px solid rgba(2, 221, 250, 0.2);
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    backdrop-filter: blur(50px);
}
h1{
    color: var(--branco-creme);
}
label {
    color: var(--branco-creme);
}

input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
}

button {
    width: 100%;
    padding: 10px;
    background: color: #333;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background-color: transparent;
        border: 2px solid rgba(255, 255, 255, 0.4);
        color: #fff;
}

.profile-photo img {
    border-radius: 50%;
    margin-top: 10px;
}

.error {
    color: red;
}

.success {
    color: #fff;
}
</style>