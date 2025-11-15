<?php
include "incs/valida-sessao.php";
require_once "src/UsuarioDAO.php";
require_once "src/MensagemDAO.php";

$idusuario = $_SESSION['idusuario'];
$usuario = UsuarioDAO::buscarPorId($idusuario);

$sugestoes = UsuarioDAO::buscarSugestoes($idusuario, 8);

// Buscar conversas do usuário
$conversas = MensagemDAO::listarConversas($idusuario);

$mensagens_nao_lidas = MensagemDAO::contarMensagensNaoLidas($idusuario);

// Se veio de um perfil, iniciar conversa
$idusuario_conversa = $_GET['idusuario'] ?? null;
$nome_busca = $_GET['nome'] ?? null;

if ($nome_busca) {
    $usuarios_busca = UsuarioDAO::buscarUsuarioNome($nome_busca, $idusuario);
    if (!empty($usuarios_busca)) {
        $idusuario_conversa = $usuarios_busca[0]['idusuario'];
    }
}

$usuario_conversa = null;
$mensagens = [];

if ($idusuario_conversa) {
    $usuario_conversa = UsuarioDAO::buscarPorId($idusuario_conversa);
    $mensagens = MensagemDAO::listarMensagens($idusuario, $idusuario_conversa);
    MensagemDAO::marcarComoLida($idusuario, $idusuario_conversa);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensagens - DogMatch</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Removendo todas as setas/pseudo-elementos das mensagens com regras mais específicas */
        .message::before,
        .message::after,
        .message-content::before,
        .message-content::after,
        .message.sent::before,
        .message.sent::after,
        .message.received::before,
        .message.received::after,
        .message-sent::before,
        .message-sent::after,
        .message-received::before,
        .message-received::after,
        div.message::before,
        div.message::after,
        div.message-content::before,
        div.message-content::after {
            display: none !important;
            content: none !important;
            content: "" !important;
            width: 0 !important;
            height: 0 !important;
            border: none !important;
            border-width: 0 !important;
            border-style: none !important;
            background: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
        }

        /* Esconder qualquer SVG ou elemento que possa estar criando setas dentro das mensagens */
        .message>svg:not(.checkmark),
        .message-content>svg:not(.checkmark) {
            display: none !important;
        }

        /* </CHANGE> */

        /* Ajustando responsividade para mobile seguindo o design das imagens de referência */

        /* Corrigir altura do messages-wrapper em todas as telas */
        .messages-wrapper {
            height: auto !important;
            min-height: calc(100vh - 8rem);
        }

        /* Usar flexbox para posicionar o chat-input-area naturalmente na parte inferior */
        .chat-wrapper {
            display: flex;
            flex-direction: column;
            height: calc(100vh - 8rem);
            position: relative;
            background: #f9f5f0;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
            background: #f9f5f0;
        }

        .chat-input-area {
            background: #f5f5f5;
            border-top: 1px solid #e0e0e0;
            padding: 1rem;
            flex-shrink: 0;
            border-radius: 12px;
            margin: 0.5rem;
        }

        /* Ajustes para tablets e menores */
        @media (max-width: 1200px) {
            body:not(.no-responsive) .messages-wrapper {
                min-height: 500px;
            }

            body:not(.no-responsive) .feed-main {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            body:not(.no-responsive) .chat-input-area {
                padding: 1rem;
                margin: 0.5rem;
            }
        }

        /* Ajustes para mobile */
        @media (max-width: 768px) {
            body:not(.no-responsive) {
                background-color: #DE720D !important;
            }

            body:not(.no-responsive) .feed-container {
                background-color: transparent !important;
                padding: 0.5rem;
            }

            body:not(.no-responsive) .feed-main {
                background: white;
                border-radius: 12px;
                padding: 0 !important;
                overflow: hidden;
            }

            body:not(.no-responsive) .messages-wrapper {
                min-height: calc(100vh - 2rem);
                margin: 0;
                border-radius: 12px;
                overflow: hidden;
            }

            body:not(.no-responsive) .chat-wrapper {
                height: calc(100vh - 2rem);
                border-radius: 12px;
                background: #f9f5f0;
            }

            body:not(.no-responsive) .conversations-header {
                padding: 1rem;
                background: #2C7A7B;
                color: white;
                border-radius: 12px 12px 0 0;
            }

            body:not(.no-responsive) .conversations-header h2 {
                font-size: 1.25rem;
                color: white;
                margin: 0;
            }

            body:not(.no-responsive) .chat-header {
                padding: 1rem;
                background: white;
                border-radius: 12px 12px 0 0;
            }

            body:not(.no-responsive) .chat-messages {
                background: #f9f5f0;
                padding: 1rem;
            }

            /* Ajustar tamanho do avatar das mensagens em mobile */
            body:not(.no-responsive) .message-avatar {
                width: 32px !important;
                height: 32px !important;
                min-width: 32px !important;
                min-height: 32px !important;
                object-fit: cover;
            }

            /* </CHANGE> */

            /* Remover setas/pseudo-elementos das mensagens em mobile com máxima especificidade */
            body:not(.no-responsive) .message::before,
            body:not(.no-responsive) .message::after,
            body:not(.no-responsive) .message-content::before,
            body:not(.no-responsive) .message-content::after,
            body:not(.no-responsive) .message.sent::before,
            body:not(.no-responsive) .message.sent::after,
            body:not(.no-responsive) .message.received::before,
            body:not(.no-responsive) .message.received::after,
            body:not(.no-responsive) .message-sent::before,
            body:not(.no-responsive) .message-sent::after,
            body:not(.no-responsive) .message-received::before,
            body:not(.no-responsive) .message-received::after,
            body:not(.no-responsive) div.message::before,
            body:not(.no-responsive) div.message::after {
                display: none !important;
                content: none !important;
                content: "" !important;
                width: 0 !important;
                height: 0 !important;
                border: none !important;
                border-width: 0 !important;
                border-style: none !important;
                background: none !important;
                opacity: 0 !important;
                visibility: hidden !important;
            }

            /* Esconder SVGs que possam estar criando setas */
            body:not(.no-responsive) .message>svg:not(.checkmark),
            body:not(.no-responsive) .message-content>svg:not(.checkmark) {
                display: none !important;
            }

            /* </CHANGE> */

            /* Ajustar espaçamento das mensagens sem setas */
            body:not(.no-responsive) .message {
                margin-bottom: 0.75rem !important;
            }

            body:not(.no-responsive) .chat-input-area {
                background: #f5f5f5;
                padding: 0.75rem;
                margin: 0.5rem;
                border-radius: 12px;
                border: none;
            }

            /* Ajustar layout do input para mobile - esconder label do clipe, mostrar input file nativo, campo de texto maior */
            body:not(.no-responsive) .message-file-label {
                display: none !important;
            }

            body:not(.no-responsive) .message-file-input {
                display: block !important;
                width: 100%;
                max-width: 130px;
                margin-bottom: 0.5rem;
                padding: 0.4rem 0.5rem;
                background: white;
                border: 1px solid #e0e0e0;
                border-radius: 6px;
                font-size: 0.7rem;
            }

            body:not(.no-responsive) .chat-input-area form {
                display: flex;
                flex-direction: column;
                gap: 8px;
                width: 100%;
            }

            body:not(.no-responsive) .chat-input-area form>div:last-child {
                display: flex !important;
                gap: 8px !important;
                width: 100% !important;
                align-items: stretch !important;
            }

            body:not(.no-responsive) .chat-input {
                flex: 1 !important;
                min-width: 0 !important;
                font-size: 0.9rem !important;
                padding: 10px 12px !important;
                border: 1px solid #e0e0e0 !important;
                border-radius: 8px !important;
                background: white !important;
            }

            body:not(.no-responsive) .chat-send-btn {
                flex-shrink: 0 !important;
                padding: 10px 16px !important;
                font-size: 0.85rem !important;
                white-space: nowrap !important;
                min-width: fit-content !important;
                max-width: 80px !important;
            }

            /* </CHANGE> */

            body:not(.no-responsive) .no-conversations {
                background: #f9f5f0;
                padding: 2rem;
                text-align: center;
                color: #666;
            }

            body:not(.no-responsive) .conversations-list-wrapper {
                background: #f9f5f0;
            }

            body:not(.no-responsive) .conversation-item {
                padding: 0.75rem 1rem;
                background: white;
                margin: 0.5rem;
                border-radius: 8px;
            }
        }

        /* Ajustes para telas muito pequenas */
        @media (max-width: 480px) {
            body:not(.no-responsive) .feed-container {
                padding: 0.25rem;
            }

            body:not(.no-responsive) .messages-wrapper {
                min-height: calc(100vh - 1rem);
                border-radius: 8px;
            }

            body:not(.no-responsive) .chat-wrapper {
                height: calc(100vh - 1rem);
                border-radius: 8px;
                background: #f9f5f0;
            }

            body:not(.no-responsive) .conversations-header {
                padding: 0.75rem;
                border-radius: 8px 8px 0 0;
            }

            body:not(.no-responsive) .conversations-header h2 {
                font-size: 1.1rem;
            }

            body:not(.no-responsive) .chat-header {
                padding: 0.75rem;
                border-radius: 8px 8px 0 0;
            }

            body:not(.no-responsive) .conversation-avatar,
            body:not(.no-responsive) .conversation-avatar-placeholder {
                width: 40px;
                height: 40px;
            }

            body:not(.no-responsive) .chat-header-avatar {
                width: 35px;
                height: 35px;
            }

            body:not(.no-responsive) .chat-messages {
                padding: 0.75rem;
            }

            /* Ajustar tamanho do avatar ainda menor em telas muito pequenas */
            body:not(.no-responsive) .message-avatar {
                width: 28px !important;
                height: 28px !important;
                min-width: 28px !important;
                min-height: 28px !important;
            }

            body:not(.no-responsive) .message {
                max-width: 85%;
            }

            /* Ajustar tamanhos para telas muito pequenas - campo de arquivo menor, texto e botão adequados */
            body:not(.no-responsive) .message-file-input {
                max-width: 110px;
                font-size: 0.65rem !important;
                padding: 0.35rem 0.4rem !important;
            }

            body:not(.no-responsive) .chat-input {
                font-size: 0.85rem !important;
                padding: 9px 10px !important;
            }

            body:not(.no-responsive) .chat-send-btn {
                padding: 9px 14px !important;
                font-size: 0.8rem !important;
                max-width: 75px !important;
            }

            body:not(.no-responsive) .chat-input-area {
                padding: 0.6rem;
                margin: 0.4rem;
            }

            body:not(.no-responsive) .chat-input-area form>div:last-child {
                gap: 6px !important;
            }

            /* </CHANGE> */
        }

        /* --- NOVO CSS ADICIONADO AQUI PARA REDUZIR O CHECKMARK NO MOBILE --- */
        @media (max-width: 768px) {

            /* Regras de alta especificidade para reduzir o ícone SVG de visualização */
            body:not(.no-responsive) .message-status .checkmark,
            body:not(.no-responsive) .message-status .checkmark.double {
                width: 14px !important;
                height: 14px !important;
                stroke-width: 2.5px !important;
                margin-left: 2px !important;
                margin-bottom: -1px !important;
            }

            /* Cor opcional para o checkmark visualizado ficar mais proeminente */
            body:not(.no-responsive) .message.sent .message-status .checkmark.visualizada {
                stroke: #34B7F1 !important;
            }
        }

        /* --- FIM DO NOVO CSS ADICIONADO --- */
    </style>
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

                <a href="mensagens.php" class="feed-nav-item active" style="position: relative;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <span>Mensagens</span>
                    <span id="mensagens-badge" style="position: absolute; top: 8px; left: 20px; background: #DE720D; color: white; border-radius: 50%; width: 20px; height: 20px; display: <?= $mensagens_nao_lidas > 0 ? 'flex' : 'none' ?>; align-items: center; justify-content: center; font-size: 11px; font-weight: bold;">
                        <?= $mensagens_nao_lidas > 9 ? '9+' : $mensagens_nao_lidas ?>
                    </span>
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

        <main class="feed-main">
            <div class="messages-wrapper">
                <?php if ($usuario_conversa) { ?>
                    <div class="chat-wrapper">
                        <div class="chat-header">
                            <a href="mensagens.php" style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 8px;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="19" y1="12" x2="5" y2="12"></line>
                                    <polyline points="12 19 5 12 12 5"></polyline>
                                </svg>
                            </a>
                            <?php if (!empty($usuario_conversa['foto'])) { ?>
                                <img src="uploads/<?= htmlspecialchars($usuario_conversa['foto']) ?>" class="chat-header-avatar" alt="<?= htmlspecialchars($usuario_conversa['nome']) ?>">
                            <?php } else { ?>
                                <div class="conversation-avatar-placeholder">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </div>
                            <?php } ?>
                            <div class="chat-header-name"><?= htmlspecialchars($usuario_conversa['nome']) ?></div>
                        </div>

                        <div class="chat-messages" id="chatMessages">
                            <?php foreach ($mensagens as $msg) {
                                $eh_enviada = $msg['idremetente'] == $idusuario;
                            ?>
                                <div class="message <?= $eh_enviada ? 'sent' : 'received' ?>" data-message-id="<?= $msg['idmensagem'] ?>">
                                    <?php
                                    $foto_msg = $eh_enviada ? $usuario['foto'] : $usuario_conversa['foto'];
                                    if (!empty($foto_msg)) { ?>
                                        <img src="uploads/<?= htmlspecialchars($foto_msg) ?>" class="message-avatar" alt="Avatar">
                                    <?php } ?>
                                    <div class="message-content">
                                        <?php if (!empty($msg['conteudo'])) { ?>
                                            <p class="message-text"><?= nl2br(htmlspecialchars($msg['conteudo'])) ?></p>
                                        <?php } ?>
                                        <?php if (!empty($msg['imagem'])) { ?>
                                            <img src="<?= htmlspecialchars($msg['imagem']) ?>"
                                                alt="Imagem"
                                                class="message-image-preview"
                                                onclick="window.open('<?= htmlspecialchars($msg['imagem']) ?>', '_blank')"
                                                style="cursor: pointer; max-width: 250px; border-radius: 8px; margin-top: 0.5rem;">
                                        <?php } ?>
                                        <div class="message-time">
                                            <?= date('d/m/Y H:i', strtotime($msg['data_envio'])) ?>
                                            <?php if ($eh_enviada) { ?>
                                                <span class="message-status">
                                                    <?php if ($msg['visualizada']) { ?>
                                                        <svg class="checkmark visualizada" viewBox="0 0 24 24">
                                                            <polyline points="20 6 9 17 4 12"></polyline>
                                                        </svg>
                                                        <svg class="checkmark double visualizada" viewBox="0 0 24 24">
                                                            <polyline points="20 6 9 17 4 12"></polyline>
                                                        </svg>
                                                    <?php } else { ?>
                                                        <svg class="checkmark" viewBox="0 0 24 24">
                                                            <polyline points="20 6 9 17 4 12"></polyline>
                                                        </svg>
                                                    <?php } ?>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="chat-input-area">
                            <form id="messageForm" action="enviar-mensagem.php" method="POST" enctype="multipart/form-data" style="display: flex; gap: 10px; width: 100%; flex-direction: column;">
                                <input type="hidden" name="iddestinatario" value="<?= $idusuario_conversa ?>">

                                <div id="imagePreviewContainer" style="display: none; position: relative; max-width: 150px;">
                                    <img id="imagePreview" src="/placeholder.svg" alt="Preview" style="max-width: 150px; max-height: 150px; border-radius: 8px; object-fit: cover;">
                                    <button type="button" onclick="removeImagePreview()" style="position: absolute; top: -8px; right: -8px; background: #DE720D; color: white; border: none; border-radius: 50%; width: 24px; height: 24px; cursor: pointer; font-weight: bold; line-height: 1;">×</button>
                                </div>

                                <div style="display: flex; gap: 10px; width: 100%;">
                                    <label for="imageUpload" class="message-file-label" title="Anexar imagem">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48" />
                                        </svg>
                                    </label>
                                    <input type="file" id="imageUpload" name="imagem" accept="image/*" class="message-file-input" onchange="previewImageUpload(this)">

                                    <input type="text" class="chat-input" id="messageInput" name="mensagem" placeholder="Digite uma mensagem..." />
                                    <button type="submit" class="chat-send-btn">Enviar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="conversations-header">
                        <h2>Mensagens</h2>
                    </div>
                    <div class="conversations-list-wrapper">
                        <?php if (empty($conversas)) { ?>
                            <div class="no-conversations">
                                Nenhuma conversa ainda
                            </div>
                        <?php } else { ?>
                            <?php foreach ($conversas as $conversa) { ?>
                                <a href="mensagens.php?idusuario=<?= $conversa['idusuario'] ?>" class="conversation-item">
                                    <?php if (!empty($conversa['foto'])) { ?>
                                        <img src="uploads/<?= htmlspecialchars($conversa['foto']) ?>" class="conversation-avatar" alt="<?= htmlspecialchars($conversa['nome']) ?>">
                                    <?php } else { ?>
                                        <div class="conversation-avatar-placeholder">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="12" cy="7" r="4"></circle>
                                            </svg>
                                        </div>
                                    <?php } ?>
                                    <div class="conversation-info">
                                        <div class="conversation-name"><?= htmlspecialchars($conversa['nome']) ?></div>
                                        <div class="conversation-last-message"><?= htmlspecialchars($conversa['ultima_mensagem'] ?? 'Iniciar conversa') ?></div>
                                    </div>
                                    <?php if ($conversa['nao_lidas'] > 0) { ?>
                                        <span class="conversation-badge">
                                            <?= $conversa['nao_lidas'] > 9 ? '9+' : $conversa['nao_lidas'] ?>
                                        </span>
                                    <?php } ?>
                                </a>
                            <?php } ?>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </main>

        <aside class="feed-suggestions">
            <div class="feed-suggestions-header">
                <?php if (!empty($usuario['foto']) && file_exists("uploads/" . $usuario['foto'])) { ?>
                    <img src="uploads/<?= htmlspecialchars($usuario['foto']) ?>" alt="Foto de perfil"
                        class="feed-suggestions-avatar">
                <?php } else { ?>
                    <div class="feed-suggestions-avatar-placeholder">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                <?php } ?>
                <a href="perfil.php?idusuario=<?= htmlspecialchars($idusuario) ?>">
                    <span><?= htmlspecialchars($usuario['nome']) ?></span>
                </a>
            </div>

            <h3 class="feed-suggestions-title">Sugestões para você:</h3>

            <div class="feed-suggestions-list">
                <?php if (empty($sugestoes)) { ?>
                    <p class="feed-suggestions-empty">Nenhuma sugestão no momento</p>
                <?php } else { ?>
                    <?php foreach ($sugestoes as $sugestao) { ?>
                        <div class="feed-suggestion-item">
                            <?php if (!empty($sugestao['foto']) && file_exists("uploads/" . $sugestao['foto'])) { ?>
                                <img src="uploads/<?= htmlspecialchars($sugestao['foto']) ?>" alt="Foto de perfil"
                                    class="feed-suggestion-avatar">
                            <?php } else { ?>
                                <div class="feed-suggestion-avatar-placeholder">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </div>
                            <?php } ?>
                            <div class="feed-suggestion-info">
                                <a href="perfil.php?idusuario=<?= htmlspecialchars($sugestao['idusuario']) ?>"
                                    class="feed-suggestion-name-link">
                                    <span class="feed-suggestion-name"><?= htmlspecialchars($sugestao['nome']) ?></span>
                                </a>
                            </div>
                            <a href="seguir.php?idseguido=<?= $sugestao['idusuario'] ?>" class="feed-btn-seguir">Seguir</a>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>

            <a href="usuarios.php" class="feed-btn-ver-mais">Ver Mais</a>
        </aside>
    </div>

    <script>
        const idusuarioConversa = <?= $idusuario_conversa ?? 'null' ?>;
        const idusuarioLogado = <?= $idusuario ?>;
        let ultimaMensagemId = 0;
        let pollingInterval;
        let visualizacaoInterval;
        let isSubmitting = false;

        <?php if (!empty($mensagens)) { ?>
            ultimaMensagemId = <?= end($mensagens)['idmensagem'] ?? 0 ?>;
        <?php } ?>

        function atualizarContadorMensagens() {
            fetch('api-contador-mensagens.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const badge = document.getElementById('mensagens-badge');
                        if (badge) {
                            if (data.contador > 0) {
                                badge.style.display = 'flex';
                                badge.textContent = data.contador > 9 ? '9+' : data.contador;
                            } else {
                                badge.style.display = 'none';
                            }
                        }
                    }
                })
                .catch(error => console.error('Erro ao atualizar contador:', error));
        }

        function verificarVisualizacao() {
            if (!idusuarioConversa) return;

            fetch(`api-verificar-visualizacao.php?idusuario_conversa=${idusuarioConversa}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.mensagens_visualizadas.length > 0) {
                        data.mensagens_visualizadas.forEach(idmensagem => {
                            const messageDiv = document.querySelector(`.message[data-message-id="${idmensagem}"]`);
                            if (messageDiv) {
                                const statusSpan = messageDiv.querySelector('.message-status');
                                if (statusSpan) {
                                    const jaVisualizada = statusSpan.querySelector('.checkmark.visualizada');
                                    if (!jaVisualizada) {
                                        statusSpan.innerHTML = `
                                            <svg class="checkmark visualizada" viewBox="0 0 24 24">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg>
                                            <svg class="checkmark double visualizada" viewBox="0 0 24 24">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg>
                                        `;
                                    }
                                }
                            }
                        });
                    }
                })
                .catch(error => console.error('Erro ao verificar visualização:', error));
        }

        function previewImageUpload(input) {
            const preview = document.getElementById('imagePreview');
            const container = document.getElementById('imagePreviewContainer');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImagePreview() {
            const input = document.getElementById('imageUpload');
            const container = document.getElementById('imagePreviewContainer');
            input.value = '';
            container.style.display = 'none';
        }

        function buscarNovasMensagens() {
            if (!idusuarioConversa) return;

            fetch(`buscar-novas-mensagens.php?idusuario=${idusuarioConversa}&ultima_id=${ultimaMensagemId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.mensagens.length > 0) {
                        const chatMessages = document.getElementById('chatMessages');

                        data.mensagens.forEach(msg => {
                            const ehEnviada = msg.idremetente == idusuarioLogado;
                            const fotoMsg = ehEnviada ? '<?= addslashes($usuario['foto'] ?? '') ?>' : '<?= addslashes($usuario_conversa['foto'] ?? '') ?>';

                            const messageDiv = document.createElement('div');
                            messageDiv.className = `message ${ehEnviada ? 'sent' : 'received'}`;
                            messageDiv.setAttribute('data-message-id', msg.idmensagem);

                            let avatarHtml = '';
                            if (fotoMsg) {
                                avatarHtml = `<img src="uploads/${fotoMsg}" class="message-avatar" alt="Avatar">`;
                            }

                            let imagemHtml = '';
                            if (msg.imagem) {
                                imagemHtml = `<img src="${msg.imagem}" alt="Imagem" class="message-image-preview" onclick="window.open('${msg.imagem}', '_blank')" style="cursor: pointer; max-width: 250px; border-radius: 8px; margin-top: 0.5rem;">`;
                            }

                            // Corrigir tratamento de texto: substituir \n por <br> e escapar HTML básico
                            const conteudoTratado = msg.conteudo ? msg.conteudo.replace(/\n/g, '<br>').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;') : '';

                            // Corrigir formatação de data: usar JS para formatar
                            const dataFormatada = new Date(msg.data_envio).toLocaleString('pt-BR', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            });

                            let statusHtml = '';
                            if (ehEnviada) {
                                if (msg.visualizada) {
                                    statusHtml = `
                                <span class="message-status">
                                    <svg class="checkmark visualizada" viewBox="0 0 24 24">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <svg class="checkmark double visualizada" viewBox="0 0 24 24">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </span>
                            `;
                                } else {
                                    statusHtml = `
                                <span class="message-status">
                                    <svg class="checkmark" viewBox="0 0 24 24">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </span>
                            `;
                                }
                            }

                            messageDiv.innerHTML = `
                        ${avatarHtml}
                        <div class="message-content">
                            ${conteudoTratado ? `<p class="message-text">${conteudoTratado}</p>` : ''}
                            ${imagemHtml}
                            <div class="message-time">
                                ${dataFormatada}
                                ${ehEnviada ? statusHtml : ''}
                            </div>
                        </div>
                    `;

                            chatMessages.appendChild(messageDiv);
                            ultimaMensagemId = msg.idmensagem; // Atualizar o ID da última mensagem
                        });

                        // Rolar para o final após adicionar mensagens
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    }
                })
                .catch(error => console.error('Erro ao buscar novas mensagens:', error));
        }

        function iniciarPolling() {
            pollingInterval = setInterval(buscarNovasMensagens, 5000);
        }

        function pararPolling() {
            clearInterval(pollingInterval);
        }

        window.onload = function() {
            const chatMessages = document.getElementById('chatMessages');
            if (chatMessages) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            iniciarPolling();
            if (idusuarioConversa) {
                visualizacaoInterval = setInterval(verificarVisualizacao, 3000);
            }
        };

        window.onbeforeunload = function() {
            pararPolling();
            clearInterval(visualizacaoInterval);
        };
    </script>
</body>

</html>