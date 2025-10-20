<?php
// Inclui os arquivos necessários
include "incs/valida-sessao.php";
require_once "src/UsuarioDAO.php";
require_once "src/CachorroDAO.php";
require_once "src/Util.php";
require_once "src/PostDAO.php"; // Mantido caso você precise listar cachorros a partir daqui

// Inicia as variáveis
$idusuario_logado = $_SESSION['idusuario'];
$usuario_logado = UsuarioDAO::buscarPorId($idusuario_logado);

// ATENÇÃO: Verifique onde listarCachorrosUsuario está definido (PostDAO ou CachorroDAO)
$cachorros_usuario = CachorroDAO::listarCachorrosUsuario($idusuario_logado);

$mensagem_sucesso = '';
$mensagem_erro = '';

// --- Lógica de EDIÇÃO DE FOTO ---
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

// --- Lógica de EXCLUSÃO DE CACHORRO ---
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

// Determina qual aba deve estar ativa
$aba_ativa = $_GET['aba'] ?? 'perfil';
if (!in_array($aba_ativa, ['perfil', 'cachorros'])) {
    $aba_ativa = 'perfil';
}

// Se a exclusão redirecionou com sucesso
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* Cores base */
        :root {
            --color-primary: #679DAB;
            /* Azul Claro/Água */
            --color-secondary: #DE720D;
            /* Laranja/Marrom */
            --color-background: #F8F4ED;
            /* Cor de fundo suave */
            --color-card-bg: #FFFFFF;
            --color-text-dark: #333;
            --color-text-light: #666;
            --border-radius: 8px;
            --shadow-light: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow-medium: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Estilo do Container Principal */
        .config-container {
            max-width: 800px;
            margin: 40px auto;
            /* Aumenta a margem superior */
            background-color: var(--color-card-bg);
            padding: 40px;
            border-radius: 12px;
            box-shadow: var(--shadow-medium);
            border: 1px solid #eee;
        }

        /* Títulos */
        .config-container h1 {
            color: var(--color-primary);
            margin-bottom: 30px;
            font-size: 2rem;
            text-align: center;
        }

        .config-content h2 {
            font-size: 1.4rem;
            color: var(--color-text-dark);
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        /* Abas de Navegação */
        .config-tabs {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
        }

        .config-tabs-btn {
            padding: 12px 20px;
            cursor: pointer;
            font-weight: 600;
            color: var(--color-text-light);
            transition: color 0.3s, border-bottom 0.3s;
            margin-right: 5px;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .config-tabs-btn:hover {
            color: var(--color-primary);
        }

        .config-tabs-btn.active {
            color: var(--color-secondary);
            border-bottom: 3px solid var(--color-secondary);
        }

        /* Alertas */
        .alerta-sucesso {
            background-color: #e6f7ee;
            color: #389e62;
            border: 1px solid #b7eb8f;
            border-radius: var(--border-radius);
        }

        .alerta-erro {
            background-color: #fff1f0;
            color: #cf1322;
            border: 1px solid #ffa39e;
            border-radius: var(--border-radius);
        }

        .alerta-sucesso,
        .alerta-erro {
            padding: 15px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        /* Formulário e Controles */
        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: var(--color-text-dark);
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: var(--color-primary);
            outline: none;
        }

        /* Foto Atual */
        .current-photo {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 20px;
            border: 5px solid var(--color-primary);
            box-shadow: 0 0 0 5px rgba(103, 157, 171, 0.2);
            display: block;
            /* Para centralizar se necessário */
        }

        /* Botões */
        .btn-acao {
            padding: 12px 25px;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.1s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-salvar {
            background-color: var(--color-primary);
            color: white;
        }

        .btn-salvar:hover {
            background-color: #588796;
            transform: translateY(-1px);
        }

        .btn-excluir {
            background-color: #E74C3C;
            /* Vermelho mais forte */
            color: white;
            font-size: 0.9rem;
        }

        .btn-excluir:hover {
            background-color: #C0392B;
        }

        /* Gerenciamento de Cães */
        .dog-manager-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            background-color: #fcfcfc;
            box-shadow: var(--shadow-light);
            transition: border-color 0.3s;
        }

        .dog-manager-card:hover {
            border-color: var(--color-secondary);
        }

        .dog-manager-card img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 15px;
            border: 2px solid var(--color-secondary);
        }

        .dog-info {
            flex-grow: 1;
        }

        .dog-info strong {
            color: var(--color-primary);
            font-size: 1.1rem;
            display: block;
        }

        .dog-info small {
            color: var(--color-text-light);
        }

        .dog-actions a {
            margin-left: 10px;
        }

        /* Placeholder para foto de cão/usuário */
        .dog-placeholder {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #ccc;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--color-secondary);
        }

        .dog-placeholder i {
            color: #fff;
            font-size: 2rem;
        }
    </style>
</head>

<body style="background-color: var(--color-background);">
    <div class="feed-container">

        <aside class="feed-sidebar">
            <div class="feed-logo"><img src="img/logo.png" alt="DogMatch" onerror="this.style.display='none'">
                <h2>DogMatch</h2>
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
                                        <strong><?= htmlspecialchars($cachorro['nome']) ?></strong>
                                        <small><?= htmlspecialchars($cachorro['raca'] ?? 'Raça Desconhecida') ?></small>
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
                    // Mantém o nome do arquivo correto
                    history.pushState(null, '', `form-editar-usuario.php?aba=${tabName}`);
                });
            });

            // Ativa a aba inicial ao carregar a página
            switchTab(initialTab);
        });
    </script>
</body>

</html>