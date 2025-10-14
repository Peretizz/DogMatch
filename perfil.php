<?php
include "incs/valida-sessao.php";
require_once "src/PostDAO.php";
require_once "src/UsuarioDAO.php";
require_once "src/SeguidoDAO.php";
require_once "src/CachorroDAO.php";

// Pega o ID do usuário do perfil, se não tiver, usa o ID da sessão
$idusuario_perfil = $_GET['idusuario'];
if (!$idusuario_perfil) {
    $idusuario_perfil = $_SESSION['idusuario'];
}

$eh_proprio_perfil = ($idusuario_perfil == $_SESSION['idusuario']);

// Busca dados do usuário do perfil
$usuarios = UsuarioDAO::listarUsuarios($_SESSION['idusuario']);
$usuario_perfil = null;

if ($eh_proprio_perfil) {
    $usuario_perfil = [
        'idusuario' => $_SESSION['idusuario'],
        'nome' => $_SESSION['nome'],
        'email' => $_SESSION['email'],
        'localizacao' => $_SESSION['localizacao'],
        'foto' => $_SESSION['foto']
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

// Pega os posts do usuário
$posts = PostDAO::listarPostsUsuario($idusuario_perfil);

// Vê se já segue o usuário
$ja_segue = false;
if (!$eh_proprio_perfil) {
    $ja_segue = SeguidoDAO::jaSegue($_SESSION['idusuario'], $idusuario_perfil);
}

// Pega os cachorros do usuário
$cachorros = CachorroDAO::listar();
$cachorros_usuario = array();
foreach ($cachorros as $c) {
    if ($c['idusuario'] == $idusuario_perfil) {
        $cachorros_usuario[] = $c;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perfil - <?= $usuario_perfil['nome'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="img/logo.png" alt="logo" class="logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <?php if (isset($_SESSION['foto'])) { ?>
                    <img src="uploads/<?= $_SESSION['foto'] ?>" alt="Perfil"
                        style="width: 40px; height: 40px; border-radius: 50%;">
                <?php } else { ?>
                    <i class="bi bi-person-circle" style="color: #d4ebf8; font-size: 1.5rem;"></i>
                <?php } ?>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Feed</a></li>
                    <li class="nav-item"><a class="nav-link active" href="perfil.php">Perfil</a></li>
                    <li class="nav-item"><a class="nav-link" href="form-criar-post.php">Criar Post</a></li>
                    <li class="nav-item"><a class="nav-link" href="form-cadastro-cachorro.php">Cadastrar Cachorro</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="usuarios.php">Explorar Usuários</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"
                            onclick="return confirm('Tem certeza de que deseja sair?');">Sair</a>
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
                        <?php if ($usuario_perfil['foto']) { ?>
                            <div class="mb-3">
                                <img src="uploads/<?= $usuario_perfil['foto'] ?>" alt="Foto de perfil"
                                    style="width: 100px; height: 100px; border-radius: 50%;">
                            </div>
                        <?php } ?>
                        <h2><?= $usuario_perfil['nome'] ?></h2>
                        <p><i class="bi bi-geo-alt-fill"></i> <?= $usuario_perfil['localizacao'] ?></p>
                        <?php if ($eh_proprio_perfil) { ?>
                            <p><i class="bi bi-envelope-fill"></i> <?= $usuario_perfil['email'] ?></p>
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
                        <?php if ($eh_proprio_perfil) { ?>
                            <div class="mt-3">
                                <a href="form-criar-post.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i>
                                    Criar Post</a>
                            </div>
                        <?php } else { ?>
                            <div class="mt-3">
                                <?php if ($ja_segue) { ?>
                                    <a href="parar-seguir.php?idseguido=<?= $idusuario_perfil ?>" class="btn btn-primary">Deixar
                                        de Seguir</a>
                                <?php } else { ?>
                                    <a href="seguir.php?idseguido=<?= $idusuario_perfil ?>" class="btn btn-primary">Seguir</a>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php if (count($cachorros_usuario) > 0) { ?>
                    <div class="dogs-section">
                        <h3>Cachorros</h3>
                        <?php foreach ($cachorros_usuario as $cachorro) { ?>
                            <div class="dog-card d-flex align-items-center">
                                <?php if ($cachorro['foto']) { ?>
                                    <img src="uploads/<?= $cachorro['foto'] ?>" alt="<?= $cachorro['nome'] ?>">
                                <?php } ?>
                                <div>
                                    <strong><?= $cachorro['nome'] ?></strong><br>
                                    <small>Raça: <?= $cachorro['raca'] ?> | Idade: <?= $cachorro['idade'] ?> anos | Peso:
                                        <?= $cachorro['peso'] ?>kg</small>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
                <h3 style="color: #679DAB; margin-bottom: 1.5rem;">Posts</h3>
                <?php if (count($posts) == 0) { ?>
                    <div class="text-center" style="color: #d4ebf8; padding: 2rem;">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #679DAB;"></i>
                        <p>Nenhum post ainda</p>
                    </div>
                <?php } else { ?>
                    <?php foreach ($posts as $post) { ?>
                        <div class="post-card">
                            <div class="post-header">
                                <?php if (isset($post['foto_usuario'])) { ?>
                                    <img src="uploads/<?= $post['foto_usuario'] ?>" alt="<?= $post['nome_usuario'] ?>"
                                        style="width: 50px; height: 50px; border-radius: 50%;">
                                <?php } else { ?>
                                    <i class="bi bi-person-circle" style="color: #679DAB; font-size: 3rem;"></i>
                                <?php } ?>
                                <div>
                                    <div class="post-dog-name">
                                        <i class="bi bi-heart-fill" style="color: #DE720D;"></i>
                                        <?= $post['nome_cachorro'] ?>
                                        <span style="color: #9E6631;">(<?= $post['raca_cachorro'] ?>)</span>
                                    </div>
                                </div>
                                <div class="post-date">
                                    <?= date('d/m/Y H:i', strtotime($post['data_criacao'])) ?>
                                </div>
                            </div>
                            <div class="post-content">
                                <?= nl2br($post['conteudo']) ?>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>