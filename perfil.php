<?php

include "incs/valida-sessao.php";
require_once "src/PostDAO.php";
require_once "src/UsuarioDAO.php";
require_once "src/SeguidoDAO.php";
require_once "src/CachorroDAO.php";
require_once "src/MensagemDAO.php";

$idusuario_perfil = $_GET['idusuario'] ?? $_SESSION['idusuario'];
$idusuario_logado = $_SESSION['idusuario'];

$eh_proprio_perfil = ($idusuario_perfil == $idusuario_logado);

$usuario_perfil = UsuarioDAO::buscarPorId($idusuario_perfil);

if (!$usuario_perfil) {
    header("Location: index.php");
    exit;
}

$posts = PostDAO::listarPostsUsuario($idusuario_perfil);

$ja_segue = false;
if (!$eh_proprio_perfil) {
    $ja_segue = SeguidoDAO::jaSegue($idusuario_logado, $idusuario_perfil);
}

$cachorros = CachorroDAO::listar();
$cachorros_usuario = array();
foreach ($cachorros as $c) {
    if ($c['idusuario'] == $idusuario_perfil) {
        $cachorros_usuario[] = $c;
    }
}

$seguidores_count = SeguidoDAO::contarSeguidores($idusuario_perfil);
$seguindo_count = SeguidoDAO::contarSeguindo($idusuario_perfil);

$seguidores = SeguidoDAO::listarSeguidores($idusuario_perfil);
$seguindo = SeguidoDAO::listarSeguindo($idusuario_perfil);

$usuario_logado = UsuarioDAO::buscarPorId($idusuario_logado);
$mensagens_nao_lidas = MensagemDAO::contarMensagensNaoLidas($idusuario_logado);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - <?= htmlspecialchars($usuario_perfil['nome']) ?></title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="feed-container">

        <aside class="feed-sidebar">
            <div class="feed-logo">
                <img src="img/logo.png" alt="DogMatch">
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

                <a href="mensagens.php" class="feed-nav-item" style="position: relative;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <span>Mensagens</span>
                    <?php if ($mensagens_nao_lidas > 0) { ?>
                        <span style="position: absolute; top: 8px; left: 20px; background: #DE720D; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: bold;">
                            <?= $mensagens_nao_lidas > 9 ? '9+' : $mensagens_nao_lidas ?>
                        </span>
                    <?php } ?>
                </a>

                <a href="buscar-cachorros.php" class="feed-nav-item">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <span>Buscar Cães</span>
                </a>
                <a href="perfil.php" class="feed-nav-item <?= $eh_proprio_perfil ? 'active' : '' ?>">
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
                                <div class="stat-item" onclick="openModal('seguidores')">
                                    <div class="stat-number"><?= htmlspecialchars($seguidores_count) ?></div>
                                    <div class="stat-label">Seguidores</div>
                                </div>
                                <div class="stat-item" onclick="openModal('seguindo')">
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
                                    <a href="mensagens.php?idusuario=<?= htmlspecialchars($idusuario_perfil) ?>" class="btn btn-follow">
                                        <i class="bi bi-chat-fill"></i> Mensagem
                                    </a>
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
                                    $id_post = $post['idpost'] ?? null;
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
                                        <strong style="color:#36798A"><?= htmlspecialchars($cachorro['nome']) ?></strong>
                                        
                                        <span style="color: <?= $cachorro['sexo'] == 'Macho' ? '#1E6BA8' : '#E91E63' ?>; font-weight: bold; margin-left: 8px;">
                                            <?= $cachorro['sexo'] == 'Macho' ? '♂' : '♀' ?>
                                        </span>
                                        <br>
                                        <small style="color: #555;">
                                            Raça: <?= htmlspecialchars($cachorro['raca']) ?> | 
                                            Sexo: <?= htmlspecialchars($cachorro['sexo'] ?? 'Não informado') ?> | 
                                            Idade: <?= htmlspecialchars($cachorro['idade']) ?> anos | 
                                            Peso: <?= htmlspecialchars($cachorro['peso']) ?>kg
                                        </small>
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

    <div class="modal-overlay" id="modal-seguidores">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Seguidores</h3>
                <button class="modal-close" onclick="closeModal('seguidores')">&times;</button>
            </div>
            <div class="modal-body">
                <?php if (empty($seguidores)) { ?>
                    <p style="text-align: center; color: #666;">Nenhum seguidor ainda.</p>
                <?php } else { ?>
                    <?php foreach ($seguidores as $seguidor) { ?>
                        <div class="user-item">
                            <?php if (!empty($seguidor['foto'])) { ?>
                                <img src="uploads/<?= htmlspecialchars($seguidor['foto']) ?>" alt="<?= htmlspecialchars($seguidor['nome']) ?>">
                            <?php } else { ?>
                                <div class="user-item-placeholder">
                                    <i class="bi bi-person-circle" style="font-size: 2rem; color: #679DAB;"></i>
                                </div>
                            <?php } ?>
                            <div class="user-item-info">
                                <a href="perfil.php?idusuario=<?= htmlspecialchars($seguidor['idusuario']) ?>" class="user-item-name-link">
                                    <div class="user-item-name"><?= htmlspecialchars($seguidor['nome']) ?></div>
                                </a>
                            </div>
                            <?php if ($seguidor['idusuario'] != $idusuario_logado) { ?>
                                <a href="mensagens.php?idusuario=<?= htmlspecialchars($seguidor['idusuario']) ?>" class="btn-message">
                                    <i class="bi bi-chat-fill"></i> Mensagem
                                </a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="modal-seguindo">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Seguindo</h3>
                <button class="modal-close" onclick="closeModal('seguindo')">&times;</button>
            </div>
            <div class="modal-body">
                <?php if (empty($seguindo)) { ?>
                    <p style="text-align: center; color: #666;">Não está seguindo ninguém ainda.</p>
                <?php } else { ?>
                    <?php foreach ($seguindo as $seg) { ?>
                        <div class="user-item">
                            <?php if (!empty($seg['foto'])) { ?>
                                <img src="uploads/<?= htmlspecialchars($seg['foto']) ?>" alt="<?= htmlspecialchars($seg['nome']) ?>">
                            <?php } else { ?>
                                <div class="user-item-placeholder">
                                    <i class="bi bi-person-circle" style="font-size: 2rem; color: #679DAB;"></i>
                                </div>
                            <?php } ?>
                            <div class="user-item-info">
                                <a href="perfil.php?idusuario=<?= htmlspecialchars($seg['idusuario']) ?>" class="user-item-name-link">
                                    <div class="user-item-name"><?= htmlspecialchars($seg['nome']) ?></div>
                                </a>
                            </div>
                            <?php if ($seg['idusuario'] != $idusuario_logado) { ?>
                                <a href="mensagens.php?idusuario=<?= htmlspecialchars($seg['idusuario']) ?>" class="btn-message">
                                    <i class="bi bi-chat-fill"></i> Mensagem
                                </a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
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

            switchTab(postsBtn, cachorrosBtn, postsContent, cachorrosContent);
        });
        
        function openModal(type) {
            const modal = document.getElementById('modal-' + type);
            modal.classList.add('active');
        }
        
        function closeModal(type) {
            const modal = document.getElementById('modal-' + type);
            modal.classList.remove('active');
        }
        
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('active');
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
