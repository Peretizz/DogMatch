<?php
include "incs/valida-sessao.php";
require_once "src/PostDAO.php";

// Pega os posts dos usuários que você segue
$posts = PostDAO::listarPostsSeguidos($_SESSION['idusuario']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feed - DogMatch</title>
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
                    <img src="uploads/<?= $_SESSION['foto'] ?>" alt="Perfil" style="width: 40px; height: 40px; border-radius: 50%;">
                <?php } else { ?>
                    <i class="bi bi-person-circle" style="color: #d4ebf8; font-size: 1.5rem;"></i>
                <?php } ?>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Feed</a></li>
                    <li class="nav-item"><a class="nav-link" href="perfil.php">Perfil</a></li>
                    <li class="nav-item"><a class="nav-link" href="form-criar-post.php">Criar Post</a></li>
                    <li class="nav-item"><a class="nav-link" href="form-cadastro-cachorro.php">Cadastrar Cachorro</a></li>
                    <li class="nav-item"><a class="nav-link" href="usuarios.php">Explorar Usuários</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php" onclick="return confirm('Tem certeza de que deseja sair?');">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main class="container my-5">
        <h1 class="text-center mb-4">Feed</h1>
        <?php if (isset($_SESSION['msg'])) { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['msg'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['msg']); ?>
        <?php } ?>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php if (count($posts) == 0) { ?>
                    <div class="no-posts">
                        <i class="bi bi-inbox" style="font-size: 4rem; color: #679DAB;"></i>
                        <h3 style="color: #679DAB; margin-top: 1rem;">Nenhum post ainda</h3>
                        <p>Siga outros usuários para ver posts no seu feed ou crie seu primeiro post!</p>
                        <a href="usuarios.php" class="btn btn-primary me-2">Explorar Usuários</a>
                        <a href="form-criar-post.php" class="btn btn-primary">Criar Post</a>
                    </div>
                <?php } else { ?>
                    <?php foreach ($posts as $post) { ?>
                        <div class="post-card">
                            <div class="post-header">
                                <?php if (isset($post['foto_usuario'])) { ?>
                                    <img src="uploads/<?= $post['foto_usuario'] ?>" alt="<?= $post['nome_usuario'] ?>" style="width: 50px; height: 50px; border-radius: 50%;">
                                <?php } else { ?>
                                    <i class="bi bi-person-circle" style="color: #679DAB; font-size: 3rem;"></i>
                                <?php } ?>
                                <div class="post-user-info">
                                    <a href="perfil.php?idusuario=<?= $post['idusuario'] ?>" class="post-user-name">
                                        <?= $post['nome_usuario'] ?>
                                    </a>
                                    <div class="post-dog-name">
                                        <i class="bi bi-heart-fill" style="color: #DE720D;"></i>
                                        <?= $post['nome_cachorro'] ?> 
                                        <?php if (isset($post['raca_cachorro'])) { ?>
                                            <span style="color: #9E6631;">(<?= $post['raca_cachorro'] ?>)</span>
                                        <?php } ?>
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
        <a href="form-criar-post.php" class="btn-criar-post" title="Criar novo post">
            <i class="bi bi-plus-lg"></i>
        </a>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>