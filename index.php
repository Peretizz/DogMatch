<?php
include "incs/valida-sessao.php";
require_once "src/PostDAO.php";

$posts = PostDAO::listarPostsSeguidos($_SESSION['idusuario']);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feed - DogMatch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .post-card {
            background-color: #253033;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }

        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            gap: 1rem;
        }

        .post-user-info {
            flex: 1;
        }

        .post-user-name {
            color: #679DAB;
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
        }

        .post-user-name:hover {
            color: #DE720D;
        }

        .post-dog-name {
            color: #d4ebf8;
            font-size: 0.9rem;
        }

        .post-date {
            color: #9E6631;
            font-size: 0.85rem;
        }

        .post-content {
            color: #d4ebf8;
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        .post-image {
            width: 100%;
            border-radius: 0.5rem;
            margin-top: 1rem;
        }

        .no-posts {
            text-align: center;
            padding: 3rem;
            color: #d4ebf8;
        }

        .btn-criar-post {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #36798a;
            color: #d4ebf8;
            border: none;
            font-size: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .btn-criar-post:hover {
            background-color: #DE720D;
            transform: scale(1.1);
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="img/logo.png" alt="logo" class="logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <?php if (isset($_SESSION['foto']) && $_SESSION['foto']) { ?>
                    <img src="uploads/<?= $_SESSION['foto'] ?>" alt="Perfil" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
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
        <h1 class="text-center mb-4">Feed</h1>

        <?php 
        if (isset($_SESSION['msg'])) { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['msg'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['msg']); ?>
        <?php } ?>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php 
                if (empty($posts)) { ?>
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
                                <div class="post-user-info">
                                    <a href="perfil.php?idusuario=<?= $post['idusuario'] ?>" class="post-user-name">
                                        <?= htmlspecialchars($post['nome_usuario']) ?>
                                    </a>
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

        <a href="form-criar-post.php" class="btn-criar-post" title="Criar novo post">
            <i class="bi bi-plus-lg"></i>
        </a>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>

</html>
