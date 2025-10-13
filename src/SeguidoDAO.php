    <?php
    require_once "ConexaoBD.php";
    require_once "Util.php";

    class SeguidoDAO
    {

        public static function seguir($idusuario, $idseguido)
        {
            $conexao = ConexaoBD::conectar();
            $sql = "insert into seguidos (idusuario, idseguido) values (:idusuario, :idseguido)";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':idusuario', $idusuario);
            $stmt->bindParam(':idseguido', $idseguido);
            $stmt->execute(); 
        }

        public static function deixarDeSeguir($idusuario, $idseguido)
        {
            $conexao = ConexaoBD::conectar();

            $sql = "DELETE FROM seguidos WHERE idusuario = ? AND idseguido = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(1, $idusuario);
            $stmt->bindParam(2, $idseguido);
            $stmt->execute();
        }
        public static function listarSeguidores($idusuario)
        {
            $conexao = ConexaoBD::conectar();

            $sql = "SELECT u.nome_usuario, u.foto_perfil 
                FROM seguidores s
                JOIN usuarios u ON u.idusuarios = s.idseguidor
                WHERE s.idusuario = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(1, $idusuario);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        public static function listarSeguindo($idseguidor)
        {
            $conexao = ConexaoBD::conectar();

            $sql = "SELECT u.nome_usuario, u.foto_perfil
                FROM seguidores s
                JOIN usuarios u ON u.idusuarios = s.idusuario
                WHERE s.idseguidor = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(1, $idseguidor);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        // Dentro da classe SeguidoDAO
        public static function jaSegue($idusuario, $idseguido)
        {
            $conexao = ConexaoBD::conectar();

            $sql = "SELECT COUNT(*) FROM Seguidos WHERE idusuario = :idusuario AND idseguido = :idseguido";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':idusuario', $idusuario);
            $stmt->bindParam(':idseguido', $idseguido);
            $stmt->execute();

            return $stmt->fetchColumn(0) > 0;
        }
    }
    ?>