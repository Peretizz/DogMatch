<?php
include "incs/valida-sessao.php";
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Seja Bem-vindo(a)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>

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
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="perfil.php">Perfil</a></li>
                    <li class="nav-item"><a class="nav-link" href="form-cadastro-cachorro.php">Cadastrar Cachorro</a></li>
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
        <h1>Seja bem-vindo(a)!</h1>
        <p class="lead">Aqui estará o conteúdo exclusivo da área logada.</p>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>

</html>