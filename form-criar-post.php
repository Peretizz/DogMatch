<?php
include "incs/valida-sessao.php";
require_once "src/PostDAO.php";

// Buscar cachorros do usuário
$cachorros = PostDAO::listarCachorrosUsuario($_SESSION['idusuario']);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Criar Post</title>
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
                <?php if (isset($_SESSION['foto']) && $_SESSION['foto']) { ?>
                    <img src="uploads/<?= $_SESSION['foto'] ?>" alt="Perfil" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                <?php } else { ?>
                    <i class="bi bi-person-circle" style="color: #d4ebf8; font-size: 1.5rem;"></i>
                <?php } ?>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Feed</a></li>
                    <li class="nav-item"><a class="nav-link" href="perfil.php">Perfil</a></li>
                    <li class="nav-item"><a class="nav-link active" href="form-criar-post.php">Criar Post</a></li>
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

    <main class="container text-center my-5">
        <?php 
        if (empty($cachorros)) { ?>
            <div class="alert alert-warning w-75 mx-auto">
                <h4>Você ainda não tem cachorros cadastrados!</h4>
                <p>Para criar um post, você precisa cadastrar pelo menos um cachorro.</p>
                <a href="form-cadastro-cachorro.php" class="btn btn-primary">Cadastrar Cachorro</a>
            </div>
        <?php } else { ?>
            <form action="criar-post.php" method="post" class="cadastro w-75 mx-auto text-start row rounded-4"
                enctype="multipart/form-data">
                <h3 class="text-center" style="font-size: 2.5rem;">Criar Post</h3>

                <div class="mb-3 col-md-12">
                    <label class="form-label">Selecione o cachorro</label>
                    <select name="idcachorro" class="form-select form-control" required>
                        <option value="">Escolha um cachorro...</option>
                        <?php foreach ($cachorros as $cachorro) { ?>
                            <option value="<?= $cachorro['idcachorro'] ?>"><?= $cachorro['nome'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3 col-md-12">
                    <label class="form-label">Conteúdo do post</label>
                    <textarea class="form-control" name="conteudo" rows="5" 
                        placeholder="Escreva sobre seu cachorro..." required></textarea>
                </div>

                <div class="mb-3 col-md-12">
                    <label class="form-label">Foto (opcional)</label>
                    <input type="file" class="form-control" name="foto" accept="image/*">
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-lg mt-3 mb-4 w-100">Publicar</button>
                </div>
            </form>
        <?php } ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>

</html>
