<?php
include("incs/valida-sessao.php");
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Explorar Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css"> <!-- use o caminho correto se estiver diferente -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>

    <!-- Navbar idêntica ao index -->
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="img/logo.png" alt="logo" class="logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-person-circle" style="color: #d4ebf8; font-size: 1.5rem;"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto text-center">
                    <li class="nav-item"><a class="nav-link" href="perfil.php">Perfil</a></li>
                    <li class="nav-item"><a class="nav-link" href="form-cadastro-cachorro.php">Cadastrar Cachorro</a>
                    </li>
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
        <div class="user-list-container">
            <h3>Encontrar Pessoas</h3>


            <form class="busca" method="GET"
                style="background: none; box-shadow: none; padding: 0; margin-bottom: 2rem;">
                <div class="form-group">
                    <label class="form-label">Nome</label>
                    <input type="text" class="form-control" name="nome" placeholder="Nome" required>
                </div>
                <button type="submit" class="btn btn-primary"
                    style="border: 1px solid #36798a; display: inline-block; width: auto; padding: 0.5rem 1rem; background-color: #36798a;">
                    Buscar
                </button>

            </form>



            <div class="user-list" style="margin-top: 1.5rem;">
                <?php
                require_once "src/UsuarioDAO.php";

                if (!isset($_GET["nome"])) {
                    $_GET["nome"] = "";
                    $usuarios = [];
                }

                $usuarios = UsuarioDAO::buscarUsuarioNome($_GET["nome"], $_SESSION["idusuario"]);

                require_once "src/SeguidoDAO.php";

                foreach ($usuarios as $usuario) {
                    $segue = SeguidoDAO::jaSegue($_SESSION["idusuario"], $usuario["idusuario"]);
                    ?>

                    <div class="usuario">
                        <span class="mx-3"><?= $usuario["nome"] ?></span>

                        <div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>

</html>