<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Minha Página</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <?php session_start(); ?>

    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="img/logo.png" alt="logo" class="logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-person-circle" style="color: #d4ebf8; font-size: 1.5rem;"></i>
            </button>
            <div class="collapse">
            </div>
        </div>
    </nav>

    <main class="container d-flex justify-content-center align-items-center my-5 flex-grow-1">
        <div class="row w-100 align-items-center justify-content-center g-3">
            <div class="fundo col-12 col-md-5 text-center mb-3 mb-md-0">
                <img src="img/fundo.png" alt="" class="img-fluid">
            </div>
            <form action="efetua-login.php" method="post" class="login-container rounded-4 p-4 py-5 col-12 col-md-5">
                <div class="auras">
                    <span class="aura1 mb-4 text-center">Dog</span>
                    <span class="aura2 mb-4 text-center">Match</span>
                </div>
             <?php   if (isset($_SESSION['msg'])) {
            echo '<div class="alert" style="color:#FFFFFF">' . $_SESSION['msg'] . '</div>';
            unset($_SESSION['msg']);
        } else {
            echo '<div class="alert" style="color:#FFFFFF">Informe seu email e senha para entrar.</div>';
        }
      ?>
                <div class="mb-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" placeholder="Digite seu Email"
                        class="form-control" required>
                </div>

                <div class="mb-4">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="Digite sua Senha" class="form-control"
                        required>
                </div>

                <div class="d-grid gap-2 mt-5">
                    <button type="submit" class="btn btn-primary">Entrar</button>
                </div>

                <div class="receba text-center mt-5">
                    <a style="color:#ffffff">Não tem conta?</a><a href="form-cadastra-usuario.php"
                        style="color: #DE720D">CADASTRE-SE</a>
                </div>
            </form>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>
</html>