<?php
include("incs/valida-sessao.php");
require_once "src/UsuarioDAO.php";
require_once "src/SeguidoDAO.php";
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Explorar Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/mobile-menu.css">
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
                <ul class="navbar-nav ms-auto text-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">Feed</a></li>
                    <li class="nav-item"><a class="nav-link" href="perfil.php">Perfil</a></li>
                    <li class="nav-item"><a class="nav-link" href="form-criar-post.php">Criar Post</a></li>
                    <li class="nav-item"><a class="nav-link" href="form-cadastro-cachorro.php">Cadastrar Cachorro</a>
                    </li>
                    <li class="nav-item"><a class="nav-link active" href="usuarios.php">Explorar Usuários</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"
                            onclick="return confirm('Tem certeza de que deseja sair?');">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main class="container my-5">
        <div class="user-list-container">
            <h3>Encontrar Pessoas</h3>
            <form class="busca" method="GET"
                style="background: none; box-shadow: none; padding: 0; margin-bottom: 2rem;">
                <div class="form-group">
                    <label class="form-label" style="color:#FFFFFF">Nome</label>
                    <input type="text" class="form-control" name="nome" placeholder="Nome"
                        value="<?= $_GET['nome'] ?? '' ?>">
                </div>
                <button type="submit" class="btn btn-primary"
                    style="border: 1px solid #36798a; display: inline-block; width: auto; padding: 0.5rem 1rem; background-color: #36798a;">
                    Buscar
                </button>
            </form>
            <div class="user-list" style="margin-top: 1.5rem;">
                <?php
                $usuarios = array();
                if (isset($_GET["nome"]) && !empty($_GET["nome"])) {
                    $usuarios = UsuarioDAO::buscarUsuarioNome($_GET["nome"], $_SESSION["idusuario"]);
                } else {
                    $usuarios = UsuarioDAO::buscarNaoSeguidos($_SESSION["idusuario"]);
                }
                foreach ($usuarios as $usuario) {
                    $segue = SeguidoDAO::jaSegue($_SESSION["idusuario"], $usuario["idusuario"]);
                    ?>
                    <div class="usuario" style="display: flex; align-items: center; margin-bottom: 1rem;">
                        <a href="perfil.php?idusuario=<?= $usuario["idusuario"] ?>"
                            style="color: #d4ebf8; text-decoration: none; display: flex; align-items: center; flex: 1;">
                            <?php if (isset($usuario["foto"]) && !empty($usuario["foto"])) { ?>
                                <img src="uploads/<?= $usuario["foto"] ?>" alt="Perfil de <?= $usuario["nome"] ?>"
                                    style="width: 40px; height: 40px; border-radius: 50%; margin-right: 0.5rem;">
                            <?php } else { ?>
                                <i class="bi bi-person-circle" style="color: #d4ebf8; font-size: 1.5rem; margin-right: 0.5rem;"></i>
                            <?php } ?>
                            <span style="font-weight: 500;"><?= $usuario["nome"] ?></span>
                        </a>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="perfil.php?idusuario=<?= $usuario["idusuario"] ?>" class="btn btn-sm btn-outline-info"
                                style="border-color: #d4ebf8; color: #d4ebf8;">
                                Ver Perfil
                            </a>
                            <?php if ($segue) { ?>
                                <a href="parar-seguir.php?idseguido=<?= $usuario["idusuario"] ?>"
                                    class="btn-deixar-seguir">Deixar de Seguir</a>
                            <?php } else { ?>
                                <a href="seguir.php?idseguido=<?= $usuario["idusuario"] ?>" class="btn-adicionar">Seguir</a>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>