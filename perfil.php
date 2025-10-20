<?php
// Inclui os arquivos necessários
include "incs/valida-sessao.php";
require_once "src/PostDAO.php";
require_once "src/UsuarioDAO.php";
require_once "src/SeguidoDAO.php";
require_once "src/CachorroDAO.php";

// Pega o ID do usuário do perfil da URL, ou usa o ID da sessão se não especificado
$idusuario_perfil = $_GET['idusuario'] ?? $_SESSION['idusuario'];
$idusuario_logado = $_SESSION['idusuario'];

// Verifica se é o perfil do próprio usuário logado
$eh_proprio_perfil = ($idusuario_perfil == $idusuario_logado);

// Busca dados do usuário do perfil
$usuario_perfil = UsuarioDAO::buscarPorId($idusuario_perfil);

if (!$usuario_perfil) {
    // Redireciona se o usuário não for encontrado
    header("Location: index.php");
    exit;
}

// Busca os posts do usuário (incluindo o idposts para o link)
// É crucial que o método 'listarPostsUsuario' retorne o ID do post (ex: idposts)
$posts = PostDAO::listarPostsUsuario($idusuario_perfil);

// Vê se já segue o usuário (apenas se não for o próprio perfil)
$ja_segue = false;
if (!$eh_proprio_perfil) {
    $ja_segue = SeguidoDAO::jaSegue($idusuario_logado, $idusuario_perfil);
}

// Pega os cachorros do usuário
$cachorros = CachorroDAO::listar(); // Supondo que listar() retorna todos os cachorros
$cachorros_usuario = array();
foreach ($cachorros as $c) {
    if ($c['idusuario'] == $idusuario_perfil) {
        $cachorros_usuario[] = $c;
    }
}

// Puxa as contagens reais do banco de dados
$seguidores_count = SeguidoDAO::contarSeguidores($idusuario_perfil);
$seguindo_count = SeguidoDAO::contarSeguindo($idusuario_perfil);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - <?= htmlspecialchars($usuario_perfil['nome']) ?></title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .empty-state-icon {
            font-size: 3rem;
            color: #DE720D;
            display: block;
            margin-bottom: 0.5rem;
        }
    </style>
</head>

<body>
    <div class="feed-container">

        <aside class="feed-sidebar">
            <div class="feed-logo">
                <img src="img/logo.png" alt="DogMatch" onerror="this.style.display='none'">
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
                <a href="perfil.php" class="feed-nav-item active">
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

        <main class="feed-main" style="padding-top: 20px;">
            <div class="row justify-content-center">
                <div class="col-lg-12 profile-container">

                    <div class="profile-header">

                        <?php if ($usuario_perfil['foto']) { ?>
                            <div class="profile-avatar-wrapper">
                                <img src="uploads/<?= htmlspecialchars($usuario_perfil['foto']) ?>" alt="Foto de perfil">
                            </div>
                        <?php } else { ?>
                            <div class="profile-avatar-wrapper"
                                style="display: flex; align-items: center; justify-content: center; background-color: #ccc;">
                                <i class="bi bi-person-circle" style="font-size: 5rem; color: #679DAB;"></i>
                            </div>
                        <?php } ?>

                        <div class="profile-info">
                            <h2><?= htmlspecialchars($usuario_perfil['nome']) ?></h2>

                            <div class="profile-stats">
                                <div class="stat-item">
                                    <div class="stat-number"><?= count($posts) ?></div>
                                    <div class="stat-label">Publicações</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number"><?= htmlspecialchars($seguidores_count) ?></div>
                                    <div class="stat-label">Seguidores</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number"><?= htmlspecialchars($seguindo_count) ?></div>
                                    <div class="stat-label">Seguindo</div>
                                </div>
                            </div>

                            <div class="profile-actions">
                                <?php if ($eh_proprio_perfil) { ?>
                                    <a href="form-editar-usuario.php" class="btn btn-edit-suggest">
                                        <i class="bi bi-gear-fill"></i> Edite seu perfil
                                    </a>
                                    <a href="usuarios.php" class="btn btn-follow">
                                        Sugestão <i class="bi bi-people-fill"></i>
                                    </a>
                                <?php } else { ?>
                                    <?php if ($ja_segue) { ?>
                                        <a href="parar-seguir.php?idseguido=<?= htmlspecialchars($idusuario_perfil) ?>"
                                            class="btn btn-follow">
                                            Deixar de Seguir
                                        </a>
                                    <?php } else { ?>
                                        <a href="seguir.php?idseguido=<?= htmlspecialchars($idusuario_perfil) ?>"
                                            class="btn btn-follow">
                                            Seguir
                                        </a>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="profile-tabs">
                        <div class="profile-tabs-btn active" id="tab-posts-btn">Publicações</div>
                        <div class="profile-tabs-btn" id="tab-cachorros-btn">Cachorros</div>
                    </div>

                    <div class="profile-content-section" id="tab-posts-content">
                        <?php if (count($posts) == 0) { ?>
                            <div class="text-center py-4" style="color: #555;">
                                <i class="bi bi-inbox empty-state-icon"></i>
                                <p>Nenhum post ainda.</p>
                            </div>
                        <?php } else { ?>
                            <div class="dog-grid">
                                <?php foreach ($posts as $post) {
                                    // Define a chave correta
                                    $id_post = $post['idpost'] ?? null;

                                    // Verifica se a foto e o ID do post existem antes de criar o link
                                    if (isset($post['foto']) && $post['foto'] && $id_post) {
                                        ?>
                                        <a href="visualizar-post.php?idpost=<?= htmlspecialchars($id_post) ?>">
                                            <img src="uploads/<?= htmlspecialchars($post['foto']) ?>" alt="Foto do post">
                                        </a>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="profile-content-section" id="tab-cachorros-content" style="display:none;">
                        <?php if (count($cachorros_usuario) > 0) { ?>
                            <?php foreach ($cachorros_usuario as $cachorro) { ?>
                                <div class="dog-card d-flex align-items-center mb-3 p-2"
                                    style="background-color: #F8F4ED; border-radius: 8px;">
                                    <?php if (isset($cachorro['foto']) && $cachorro['foto']) { ?>
                                        <img src="uploads/<?= htmlspecialchars($cachorro['foto']) ?>"
                                            alt="<?= htmlspecialchars($cachorro['nome']) ?>">
                                    <?php } ?>
                                    <div>
                                        <strong><?= htmlspecialchars($cachorro['nome']) ?></strong><br>
                                        <small style="color: #555;">Raça: <?= htmlspecialchars($cachorro['raca']) ?> | Idade:
                                            <?= htmlspecialchars($cachorro['idade']) ?> anos | Peso:
                                            <?= htmlspecialchars($cachorro['peso']) ?>kg</small>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <div class="text-center py-4" style="color: #555;">
                                <i class="bi bi-person-bounding-box empty-state-icon"></i>
                                <p>Nenhum cachorro cadastrado.</p>
                            </div>
                        <?php } ?>
                    </div>

                </div>
            </div>
        </main>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Script para funcionalidade das Abas
            const postsBtn = document.getElementById('tab-posts-btn');
            const cachorrosBtn = document.getElementById('tab-cachorros-btn');
            const postsContent = document.getElementById('tab-posts-content');
            const cachorrosContent = document.getElementById('tab-cachorros-content');

            function switchTab(activeBtn, inactiveBtn, activeContent, inactiveContent) {
                activeBtn.classList.add('active');
                inactiveBtn.classList.remove('active');
                activeContent.style.display = 'block';
                inactiveContent.style.display = 'none';
            }

            postsBtn.addEventListener('click', function () {
                switchTab(postsBtn, cachorrosBtn, postsContent, cachorrosContent);
            });

            cachorrosBtn.addEventListener('click', function () {
                switchTab(cachorrosBtn, postsBtn, cachorrosContent, postsContent);
            });

            // Estado inicial: Garante que a aba "Publicações" esteja ativa ao carregar
            switchTab(postsBtn, cachorrosBtn, postsContent, cachorrosContent);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>