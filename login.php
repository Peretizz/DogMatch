<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Minha Página</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="img/logo.png" alt="logo" class="logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Início</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Sobre</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contato</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container d-flex justify-content-center align-items-center my-5">
        <div class="row col-12">
            <div class="col-3">
                <img src="img/fundo.png" alt="">
            </div>
            <form action="efetua-login.php" method="post" class="login-container border rounded p-4  col-4">
                <h4 class="mb-4 text-center">Login</h4>

                <?php
                session_start();
                if (isset($_SESSION['msg'])) {
                    echo '<div class="alert alert-danger" role="alert">';
                    echo $_SESSION['msg'];
                    unset($_SESSION['msg']);
                    echo '</div>';
                } else {
                    echo '<div class="alert alert-info" role="alert">';
                    echo 'Informe seu email e senha para entrar.';
                    echo '</div>';
                }
                ?>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" id="senha" name="senha" class="form-control" required>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Entrar</button>
                </div>

                <div class="text-center mt-3">
                    <a href="form-cadastra-usuario.html">Ainda não sou usuário</a>
                </div>
            </form>
        </div>
    </main>

    <footer class="text-white text-center py-3 mt-auto">
        <p class="mb-0">&copy; 2025 - DogMatch. Todos os direitos reservados. | Desenvolvido por André Nascimento, Bruno
            Falchetti e Nicolas Pereti</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>

</html>