<?php

include "incs/valida-sessao.php";
require_once "src/UsuarioDAO.php";
require_once "src/CachorroDAO.php";
require_once "src/Util.php";
require_once "src/PostDAO.php";


$idusuario_logado = $_SESSION['idusuario'];
$usuario_logado = UsuarioDAO::buscarPorId($idusuario_logado);


$cachorros_usuario = CachorroDAO::listarCachorrosUsuario($idusuario_logado);

$mensagem_sucesso = '';
$mensagem_erro = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'editar_foto') {
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $nova_foto = Util::salvarArquivo('foto');

        if (UsuarioDAO::atualizarFoto($idusuario_logado, $nova_foto)) {
            $mensagem_sucesso = "Sua foto de perfil foi atualizada com sucesso!";
            $usuario_logado = UsuarioDAO::buscarPorId($idusuario_logado);
        } else {
            $mensagem_erro = "Erro ao salvar a foto no banco de dados.";
        }
    } else {
        $mensagem_erro = "Nenhuma foto selecionada ou erro no upload.";
    }
}


if (isset($_GET['acao']) && $_GET['acao'] === 'excluir_cachorro' && isset($_GET['idcachorro'])) {
    $idcachorro_excluir = (int) $_GET['idcachorro'];

    if (CachorroDAO::excluirCachorro($idcachorro_excluir, $idusuario_logado)) {
        $mensagem_sucesso = "Cachorro excluído com sucesso!";
        header("Location: form-editar-usuario.php?aba=cachorros&sucesso=" . urlencode($mensagem_sucesso));
        exit;
    } else {
        $mensagem_erro = "Erro ao excluir o cachorro ou o cão não pertence a você.";
    }
}


$aba_ativa = $_GET['aba'] ?? 'perfil';
if (!in_array($aba_ativa, ['perfil', 'cachorros'])) {
    $aba_ativa = 'perfil';
}


if (isset($_GET['sucesso'])) {
    $mensagem_sucesso = htmlspecialchars($_GET['sucesso']);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - DogMatch</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body style="background-color: var(--color-background);">
    <div class="feed-container">

        <aside class="feed-sidebar">
            <div class="feed-logo"><img src="img/logo.png" alt="DogMatch" onerror="this.style.display='none'">
            </div>
            <nav class="feed-nav">
                 <a href="index.php" class="feed-nav-item">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    <span>Home</span>
                </a>

                <a href="form-criar-post.php" class="feed-nav-item">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="12" y1="8" x2="12" y2="16"></line>
                        <line x1="8" y1="12" x2="16" y2="12"></line>
                    </svg>
                    <span>Postar</span>
                </a>

                <a href="mensagens.php" class="feed-nav-item">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <span>Mensagens</span>
                </a>
                <a href="buscar-cachorros.php" class="feed-nav-item">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <span>Buscar Cães</span>
                </a>
                <a href="perfil.php" class="feed-nav-item">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span>Perfil</span>
                </a>
                <a href="logout.php" class="feed-nav-item" onclick="return confirm('Tem certeza de que deseja sair?');">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span>Sair</span>
                </a>
            </nav>
            <a href="form-cadastro-cachorro.php" class="feed-btn-cadastrar">Cadastrar Cachorro</a>
        </aside>

        <main class="feed-main" style="padding-top: 20px; background-color: var(--color-background);">
            <div class="config-container">
                <h1>Configurações da Conta</h1>

                <?php if ($mensagem_sucesso): ?>
                    <div class="alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
                <?php endif; ?>
                <?php if ($mensagem_erro): ?>
                    <div class="alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
                <?php endif; ?>

                <div class="config-tabs">
                    <div class="config-tabs-btn <?= $aba_ativa == 'perfil' ? 'active' : '' ?>" id="tab-perfil-btn"
                        data-tab="perfil">Editar Foto</div>
                    <div class="config-tabs-btn <?= $aba_ativa == 'cachorros' ? 'active' : '' ?>" id="tab-cachorros-btn"
                        data-tab="cachorros">Gerenciar Cães</div>
                </div>

                <div class="config-content" id="tab-perfil-content"
                    style="display: <?= $aba_ativa == 'perfil' ? 'block' : 'none' ?>;">

                    <h2>Sua Foto de Perfil</h2>

                    <form method="POST" enctype="multipart/form-data" action="form-editar-usuario.php?aba=perfil">
                        <input type="hidden" name="acao" value="editar_foto">

                        <div class="form-group" style="text-align: center;">
                            <label>Foto Atual:</label>
                            <?php if (!empty($usuario_logado['foto'])): ?>
                                <img src="uploads/<?= htmlspecialchars($usuario_logado['foto']) ?>"
                                    alt="Foto de perfil atual" class="current-photo">
                            <?php else: ?>
                                <div class="current-photo dog-placeholder" style="border: 5px solid var(--color-primary);">
                                    <i class="bi bi-person-fill" style="font-size: 3rem;"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="foto">Selecione uma nova foto:</label>
                            <input type="file" name="foto" id="foto" class="form-control" accept="image/*" required>
                        </div>

                        <div class="form-group" style="text-align: center;">
                            <button type="submit" class="btn-acao btn-salvar">
                                <i class="bi bi-cloud-upload-fill" style="margin-right: 8px;"></i>
                                Salvar Nova Foto
                            </button>
                        </div>
                    </form>
                </div>

                <div class="config-content" id="tab-cachorros-content"
                    style="display: <?= $aba_ativa == 'cachorros' ? 'block' : 'none' ?>;">

                    <h2>Gerenciar Cães Cadastrados</h2>

                    <?php if (empty($cachorros_usuario)): ?>
                        <p style="color: var(--color-text-light); text-align: center; margin-top: 30px;">
                            Você ainda não cadastrou nenhum cão.
                            <a href="form-cadastro-cachorro.php"
                                style="color: var(--color-secondary); font-weight: bold; text-decoration: none;">Cadastre um
                                agora!</a>
                        </p>
                    <?php else: ?>
                        <?php foreach ($cachorros_usuario as $cachorro): ?>
                            <div class="dog-manager-card">

                                <div style="display: flex; align-items: center;">
                                    <?php if (!empty($cachorro['foto'])): ?>
                                        <img src="uploads/<?= htmlspecialchars($cachorro['foto']) ?>"
                                            alt="<?= htmlspecialchars($cachorro['nome']) ?>">
                                    <?php else: ?>
                                        <div class="dog-placeholder">
                                            <i class="bi bi-dog"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="dog-info">
                                        <strong>
                                            <?= htmlspecialchars($cachorro['nome']) ?>
                                            <!-- Adicionando ícone de sexo -->
                                            <span style="color: <?= ($cachorro['sexo'] ?? 'Macho') == 'Macho' ? '#4A90E2' : '#E91E63' ?>; margin-left: 5px;">
                                                <?= ($cachorro['sexo'] ?? 'Macho') == 'Macho' ? '♂' : '♀' ?>
                                            </span>
                                        </strong>
                                        <!-- Adicionando sexo na descrição -->
                                        <small>
                                            <?= htmlspecialchars($cachorro['raca'] ?? 'Raça Desconhecida') ?> | 
                                            <?= htmlspecialchars($cachorro['sexo'] ?? 'Não informado') ?>
                                        </small>
                                    </div>
                                </div>

                                <div class="dog-actions">
                                    <a href="form-editar-usuario.php?acao=excluir_cachorro&idcachorro=<?= htmlspecialchars($cachorro['idcachorro']) ?>"
                                        class="btn-acao btn-excluir"
                                        onclick="return confirm('ATENÇÃO: Você tem certeza que deseja EXCLUIR o cachorro <?= htmlspecialchars($cachorro['nome']) ?>? Esta ação não pode ser desfeita.');">
                                        <i class="bi bi-trash-fill" style="margin-right: 5px;"></i>
                                        Excluir
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            </div>
        </main>

        <aside class="feed-suggestions" style="opacity: 0; pointer-events: none;"></aside>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('.config-tabs-btn');
            const contents = document.querySelectorAll('.config-content');
            const urlParams = new URLSearchParams(window.location.search);
            const initialTab = urlParams.get('aba') || 'perfil';

            function switchTab(tabName) {
                tabs.forEach(tab => {
                    tab.classList.remove('active');
                    if (tab.dataset.tab === tabName) {
                        tab.classList.add('active');
                    }
                });

                contents.forEach(content => {
                    content.style.display = 'none';
                    if (content.id === `tab-${tabName}-content`) {
                        content.style.display = 'block';
                    }
                });
            }

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const tabName = tab.dataset.tab;
                    switchTab(tabName);

                    history.pushState(null, '', `form-editar-usuario.php?aba=${tabName}`);
                });
            });


            switchTab(initialTab);
        });
    </script>
</body>

</html>
