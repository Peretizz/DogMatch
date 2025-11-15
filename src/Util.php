    <?php
    class Util
    {
        public static function salvarArquivo(string $nomeCampo)
        {
            
            $diretorioUpload = "uploads/";

            
            if (!is_dir($diretorioUpload)) {
                
                if (!mkdir($diretorioUpload, 0755, true)) {
                    
                    error_log("Erro ao criar o diretório de upload: " . $diretorioUpload);
                    return false;
                }
            }

            
            if (isset($_FILES[$nomeCampo]) && $_FILES[$nomeCampo]['error'] === UPLOAD_ERR_OK) {
                $arquivoTmp = $_FILES[$nomeCampo]['tmp_name'];
                $nomeOriginal = basename($_FILES[$nomeCampo]['name']);
                $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));

                
                $nomeUnico = uniqid("file_", true) . "." . $extensao;

                
                $caminhoFinal = $diretorioUpload . $nomeUnico;

                
                if (move_uploaded_file($arquivoTmp, $caminhoFinal)) {
                    return $nomeUnico; 
                } else {
                    error_log("Erro ao mover o arquivo: " . $nomeOriginal);
                }
            }

            
            return false;
        }
    }