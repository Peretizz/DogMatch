<?php
class Util
{
    /**
     * Salva um arquivo carregado.
     * @param string $nomeCampo O nome do campo de arquivo no formulário (ex: 'foto', 'vacinacao').
     * @return string|false Retorna o nome único do arquivo salvo ou false em caso de erro.
     */
    public static function salvarArquivo(string $nomeCampo)
    {
        // Define o diretório onde os arquivos serão salvos
        $diretorioUpload = "uploads/";

        // Verifica se o diretório existe, senão, cria
        if (!is_dir($diretorioUpload)) {
            // Tenta criar o diretório
            if (!mkdir($diretorioUpload, 0755, true)) {
                // Adicionado para tratar erro de criação de diretório
                error_log("Erro ao criar o diretório de upload: " . $diretorioUpload);
                return false;
            }
        }

        // Verifica se o arquivo foi enviado corretamente usando o nome do campo
        if (isset($_FILES[$nomeCampo]) && $_FILES[$nomeCampo]['error'] === UPLOAD_ERR_OK) {
            $arquivoTmp = $_FILES[$nomeCampo]['tmp_name'];
            $nomeOriginal = basename($_FILES[$nomeCampo]['name']);
            $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));

            // Gera um nome único para o arquivo (melhor usar um prefixo mais genérico)
            $nomeUnico = uniqid("file_", true) . "." . $extensao;

            // Caminho final
            $caminhoFinal = $diretorioUpload . $nomeUnico;

            // Move o arquivo
            if (move_uploaded_file($arquivoTmp, $caminhoFinal)) {
                return $nomeUnico; // Retorna o nome único do arquivo salvo
            } else {
                // Adicionado para tratar erro ao mover o arquivo
                error_log("Erro ao mover o arquivo: " . $nomeOriginal);
            }
        }

        // Se o arquivo não foi enviado ou houve erro no upload/movimentação, retorna false
        return false;
    }
}