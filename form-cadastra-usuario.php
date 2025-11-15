<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Página de Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="login.php">
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

    <main class="container text-center my-5">  
        <form action="cadastra-usuario.php" method="post" class="cadastro mx-auto text-start row rounded-4" enctype="multipart/form-data">
            <h3 class="text-white text-center" style="font-size: 50px;">Cadastre-se</h3>
            <?php if (isset($_SESSION['erro'])): ?>
            <div class="alert text-center" style="color:#FFFFFF">
                <?php echo $_SESSION['erro']; unset($_SESSION['erro']); ?>
            </div>
        <?php endif; ?>
            <div class="mb-3 col-md-12">
                <label class="form-label">Nome</label>
                <input type="text" class="form-control" name="nome" placeholder="Nome" required>
            </div>
            <div class="mb-3 col-md-12">
                <label class="form-label">Localização</label>
                <input type="text" class="form-control" name="localizacao" placeholder="Onde você mora" required>
            </div>
            <div class="mb-3 col-md-12">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="mb-3 col-md-12">
                <label class="form-label">Senha</label>
                <input type="password" class="form-control" name="senha" placeholder="Senha" required>
            </div>
            <div class="mb-3 col-md-12">
                <label class="form-label">Foto de Perfil</label>
                <input type="file" class="form-control" name="foto" accept="image/*" required>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-lg my-4 w-100">Cadastrar</button>
            </div>
            <div class="text-center mb-3">
                <a href="login.php" style="color: #DE720D">Já sou usuário!</a>
            </div>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>