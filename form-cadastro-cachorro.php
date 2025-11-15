<?php
include "incs/valida-sessao.php";
require_once "src/CachorroDAO.php"; 
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro de Cachorro</title>
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
                    <li class="nav-item"><a class="nav-link" href="form-criar-post.php">Criar Post</a></li>
                    <li class="nav-item"><a class="nav-link active" href="form-cadastro-cachorro.php">Cadastrar Cachorro</a></li>
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
        <form action="cadastra-cachorro.php" method="post" class="cadastro mx-auto text-start row rounded-4"
            enctype="multipart/form-data">
            <h3 class="text-center" style="font-size: 2.5rem;">Cadastre seu cachorro</h3>

            <div class="mb-3 col-md-12">
                <label class="form-label">Nome do cachorro</label>
                <input type="text" class="form-control" name="nome" placeholder="Nome" required>
            </div>

            <div class="mb-3 col-md-12">
                <label class="form-label">Foto do cachorro</label>
                <input type="file" class="form-control" name="foto" accept="image/*" required>
            </div>

            <div class="mb-3 d-flex gap-3">
                <div class="col">
                    <label class="form-label">Peso</label>
                    <input type="number" class="form-control" name="peso" placeholder="Peso do cachorro" min="1"
                        required>
                </div>
                <div class="col">
                    <label class="form-label">Raça</label>
                    <select name="idraca" class="form-select form-control" required>
                        <?php
                        $racas = CachorroDAO::listarRacas(); 
                        for ($i = 0; $i < count($racas); $i++) {
                            ?>
                            <option value="<?= $racas[$i]["idraca"] ?>"><?= $racas[$i]["nome"] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="mb-3 col-md-12">
                <label class="form-label">Sexo</label>
                <select name="idsexo" class="form-select form-control" required>
                    <?php
                    $sexos = CachorroDAO::listarSexos(); 
                    foreach ($sexos as $sexo) {
                        ?>
                        <option value="<?= $sexo["idsexo"] ?>"><?= $sexo["sexo"] ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3 col-md-12">
                <label class="form-label">Idade do cachorro</label>
                <input type="number" class="form-control" name="idade" placeholder="insira a idade do cachorro" min="0"
                    required>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-lg mt-3 mb-4 w-100">Cadastrar</button>
            </div>
        </form>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>
