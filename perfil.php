<?php
include "incs/valida-sessao.php";
require_once "src/PostDAO.php";
require_once "src/UsuarioDAO.php";
require_once "src/SeguidoDAO.php";
require_once "src/CachorroDAO.php";

$idusuario_perfil = $_GET['idusuario'] ?? $_SESSION['idusuario'];
$eh_proprio_perfil = ($idusuario_perfil == $_SESSION['idusuario']);

$usuarios = UsuarioDAO::listarUsuarios($_SESSION['idusuario']);
$usuario_perfil = null;

if ($eh_proprio_perfil) {
    $usuario_perfil = [
        'idusuario' => $_SESSION['idusuario'],
        'nome' => $_SESSION['nome'],
        'email' => $_SESSION['email'],
        'localizacao' => $_SESSION['localizacao']
    ];
} else {
    foreach ($usuarios as $u) {
        if ($u['idusuario'] == $idusuario_perfil) {
            $usuario_perfil = $u;
            break;
        }
    }
}

if (!$usuario_perfil) {
    header("Location: index.php");
    exit;
}

$posts = PostDAO::listarPostsUsuario($idusuario_perfil);

$ja_segue = false;
if (!$eh_proprio_perfil) {
    $ja_segue = SeguidoDAO::jaSegue($_SESSION['idusuario'], $idusuario_perfil);
}

$cachorros = CachorroDAO::listar();
$cachorros_usuario = array_filter($cachorros, function($c) use ($idusuario_perfil) {
    return $c['idusuario'] == $idusuario_perfil;
});
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perfil - <?= htmlspecialchars($usuario_perfil['nome']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>

    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="img/logo.png" alt="logo" class="logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-person-circle" style="color: #d4ebf8; font-size: 1.5rem;"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Feed</a></li>
                    <li class="nav-item"><a class="nav-link active" href="perfil.php">Perfil</a></li>
                    <li class="nav-item"><a class="nav-link" href="form-cadastro-cachorro.php">Cadastrar Cachorro</a></li>
                    <li class="nav-item"><a class="nav-link" href="usuarios.php">Explorar Usuários</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"
                            onclick="return confirm('Tem certeza de que deseja sair?');">
                            Sair
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="profile-header">
                    <div class="profile-info">
                        <h2><?= htmlspecialchars($usuario_perfil['nome']) ?></h2>
                        <p><i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($usuario_perfil['localizacao']) ?></p>
                        <?php 
                        if ($eh_proprio_perfil) { ?>
                            <p><i class="bi bi-envelope-fill"></i> <?= htmlspecialchars($usuario_perfil['email']) ?></p>
                        <?php } ?>

                        <div class="profile-stats">
                            <div class="stat-item">
                                <div class="stat-number"><?= count($posts) ?></div>
                                <div class="stat-label">Posts</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number"><?= count($cachorros_usuario) ?></div>
                                <div class="stat-label">Cachorros</div>
                            </div>
                        </div>

                        <?php if (!$eh_proprio_perfil) { ?>
                            <div class="mt-3">
                                <?php if ($ja_segue) { ?>
                                    <a href="parar-seguir.php?idseguido=<?= $idusuario_perfil ?>" 
                                       class="btn btn-primary">Deixar de Seguir</a>
                                <?php } else { ?>
                                    <a href="seguir.php?idseguido=<?= $idusuario_perfil ?>" 
                                       class="btn btn-primary">Seguir</a>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <?php if (!empty($cachorros_usuario)) { ?>
                    <div class="dogs-section">
                        <h3>Cachorros</h3>
                        <?php foreach ($cachorros_usuario as $cachorro) { ?>
                            <div class="dog-card d-flex align-items-center">
                                <?php if ($cachorro['foto']) { ?>
                                    <img src="uploads/<?= $cachorro['foto'] ?>" alt="<?= htmlspecialchars($cachorro['nome']) ?>">
                                <?php } ?>
                                <div>
                                    <strong><?= htmlspecialchars($cachorro['nome']) ?></strong><br>
                                    <small>Raça: <?= htmlspecialchars($cachorro['nome']) ?> | 
                                           Idade: <?= $cachorro['idade'] ?> anos | 
                                           Peso: <?= $cachorro['peso'] ?>kg</small>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>

                <h3 style="color: #679DAB; margin-bottom: 1.5rem;">Posts</h3>
                <?php if (empty($posts)) { ?>
                    <div class="text-center" style="color: #d4ebf8; padding: 2rem;">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #679DAB;"></i>
                        <p>Nenhum post ainda</p>
                    </div>
                <?php } else { ?>
                    <?php foreach ($posts as $post) { ?>
                        <div class="post-card">
                            <div class="post-header">
                                <div>
                                    <div class="post-dog-name">
                                        <i class="bi bi-heart-fill" style="color: #DE720D;"></i>
                                        <?= htmlspecialchars($post['nome_cachorro']) ?>
                                    </div>
                                </div>
                                <div class="post-date">
                                    <?= date('d/m/Y H:i', strtotime($post['data_criacao'])) ?>
                                </div>
                            </div>

                            <div class="post-content">
                                <?= nl2br(htmlspecialchars($post['conteudo'])) ?>
                            </div>

                            <?php if ($post['foto']) { ?>
                                <img src="uploads/<?= $post['foto'] ?>" alt="Foto do post" class="post-image">
                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>

</html>
