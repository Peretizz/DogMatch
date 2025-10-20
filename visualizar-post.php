<?php
// Inclui os arquivos necessários
include "incs/valida-sessao.php";
require_once "src/PostDAO.php";
require_once "src/UsuarioDAO.php"; 

// 1. Receber o ID do Post
$idpost = $_GET['idpost'] ?? null;

if (!$idpost) {
    // Redireciona se o ID do post não for fornecido
    header("Location: index.php");
    exit;
}

// 2. Buscar o Post
$post = PostDAO::buscarPorId($idpost); 

if (!$post) {
    // Redireciona se o post não for encontrado
    header("Location: index.php");
    exit;
}

// 3. Buscar o Usuário que criou o Post
$idusuario_post = $post['idusuario'];
$usuario_post = UsuarioDAO::buscarPorId($idusuario_post);

// 4. Buscar o Usuário Logado (para a sidebar)
$idusuario_logado = $_SESSION['idusuario'];
$usuario_logado = UsuarioDAO::buscarPorId($idusuario_logado);

// 5. Formatação de Data
$data_formatada = 'Data não disponível';
if (isset($post['data_criacao']) && !empty($post['data_criacao']) && $post['data_criacao'] != '0000-00-00 00:00:00') {
    $timestamp = strtotime($post['data_criacao']);
    if ($timestamp > 0) {
        $data_formatada = date('d/m/Y H:i', $timestamp);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Post - DogMatch</title>
    <link rel="stylesheet" href="css/styles.css"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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

        <main class="feed-main" style="padding-top: 20px;">
            <div class="feed-posts" style="max-width: 650px; margin: 0 auto;">
                
                <article class="feed-post" style="border: 1px solid #ddd; padding: 20px;">
                    
                    <div class="feed-post-header">
                        <?php if (!empty($usuario_post['foto']) && file_exists("uploads/" . $usuario_post['foto'])) { ?>
                            <img src="uploads/<?= htmlspecialchars($usuario_post['foto']) ?>" alt="Foto de perfil" class="feed-post-avatar">
                        <?php } else { ?>
                            <div class="feed-post-avatar-placeholder">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                        <?php } ?>
                        <div class="feed-post-user">
                            <a href="perfil.php?idusuario=<?= htmlspecialchars($usuario_post['idusuario']) ?>">
                                <span class="feed-post-username"><?= htmlspecialchars($usuario_post['nome']) ?></span>
                            </a>
                            <span class="feed-post-date"><?= $data_formatada ?></span>
                        </div>
                    </div>
                    
                    <?php if (!empty($post['conteudo'])) { ?>
                        <p class="feed-post-text"><?= nl2br(htmlspecialchars($post['conteudo'])) ?></p>
                    <?php } ?>
                    
                    <?php if (!empty($post['foto']) && file_exists("uploads/" . $post['foto'])) { ?>
                        <div class="feed-post-image" style="max-width: 100%; height: auto;">
                            <img src="uploads/<?= htmlspecialchars($post['foto']) ?>" alt="Post" style="width: 100%; height: auto; border-radius: 4px;">
                        </div>
                    <?php } ?>
                    
                    <div class="feed-post-actions">
                        <button class="feed-action-btn">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                            <span>Curtir</span>
                        </button>
                        <button class="feed-action-btn">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            <span>Comentar</span>
                        </button>
                        <button class="feed-action-btn">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path>
                                <polyline points="16 6 12 2 8 6"></polyline>
                                <line x1="12" y1="2" x2="12" y2="15"></line>
                            </svg>
                            <span>Compartilhar</span>
                        </button>
                    </div>
                    
                    <div class="post-comments-section" style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee;">
                        <p style="color: #666; font-style: italic;">Seção de comentários seria implementada aqui...</p>
                        <div class="comment-list" style="margin-top: 10px;">
                            </div>
                    </div>
                    
                </article>

            </div>
        </main>

        <aside class="feed-suggestions" style="opacity: 0; pointer-events: none;">
            </aside>
    </div>

    </body>

</html>