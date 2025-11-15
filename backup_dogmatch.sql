-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql206.infinityfree.com
-- Tempo de geração: 10/11/2025 às 20:46
-- Versão do servidor: 11.4.7-MariaDB
-- Versão do PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `if0_40322933_dogmatch`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cachorros`
--

CREATE TABLE `cachorros` (
  `idcachorro` int(11) NOT NULL,
  `nome` varchar(45) DEFAULT NULL,
  `foto` varchar(45) DEFAULT NULL,
  `peso` int(11) DEFAULT NULL,
  `idade` int(11) DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `idraca` int(11) DEFAULT NULL,
  `idsexo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cachorros`
--

INSERT INTO `cachorros` (`idcachorro`, `nome`, `foto`, `peso`, `idade`, `idusuario`, `idraca`, `idsexo`) VALUES
(19, 'Nina', '690ccf3ad6bd7.jpg', 15, 2, 17, 33, 2),
(21, 'Bernardo', '690cd0fc9e3f3.jfif', 6, 3, 17, 16, 1),
(22, 'pandora', '690cd44ef3441.jpg', 12, 5, 21, 33, 2),
(24, 'Princesa', '690cd5ccf2a0f.webp', 26, 4, 19, 29, 2),
(26, 'Choppinho', '690cd6c927f53.jpg', 20, 2, 18, 5, 1),
(27, 'Thor', '690cd99101582.webp', 30, 5, 23, 9, 1),
(31, 'Fred', '6911e88e73bbb.jpg', 5, 3, 20, 33, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `comentarios`
--

CREATE TABLE `comentarios` (
  `idcomentario` int(11) NOT NULL,
  `idpost` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `conteudo` text NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `comentarios`
--

INSERT INTO `comentarios` (`idcomentario`, `idpost`, `idusuario`, `conteudo`, `data_criacao`) VALUES
(16, 22, 17, 'Que lindo!!', '2025-11-06 17:36:16'),
(21, 22, 21, 'lindooooooooo', '2025-11-07 16:27:13');

-- --------------------------------------------------------

--
-- Estrutura para tabela `curtidas`
--

CREATE TABLE `curtidas` (
  `idcurtida` int(11) NOT NULL,
  `idpost` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `curtidas`
--

INSERT INTO `curtidas` (`idcurtida`, `idpost`, `idusuario`, `data_criacao`) VALUES
(25, 22, 23, '2025-11-06 17:24:59'),
(26, 21, 23, '2025-11-06 17:25:01'),
(27, 19, 23, '2025-11-06 17:25:03'),
(28, 18, 23, '2025-11-06 17:25:07'),
(29, 17, 23, '2025-11-06 17:25:09'),
(30, 22, 17, '2025-11-06 17:36:04'),
(32, 22, 21, '2025-11-07 16:27:16'),
(33, 24, 17, '2025-11-07 17:00:42'),
(34, 22, 38, '2025-11-09 00:14:21'),
(37, 17, 20, '2025-11-10 13:26:15'),
(38, 17, 17, '2025-11-10 13:28:56'),
(39, 27, 17, '2025-11-10 13:29:43');

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens`
--

CREATE TABLE `mensagens` (
  `idmensagem` int(11) NOT NULL,
  `idremetente` int(11) NOT NULL,
  `iddestinatario` int(11) NOT NULL,
  `conteudo` text NOT NULL,
  `data_envio` datetime NOT NULL,
  `lida` tinyint(1) DEFAULT 0,
  `visualizada` tinyint(1) DEFAULT 0,
  `imagem` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `mensagens`
--

INSERT INTO `mensagens` (`idmensagem`, `idremetente`, `iddestinatario`, `conteudo`, `data_envio`, `lida`, `visualizada`, `imagem`) VALUES
(100, 17, 20, 'Bom dia, como você está?', '2025-11-10 10:30:33', 1, 1, NULL),
(101, 20, 17, 'Bom dia, tudo certo e com você?', '2025-11-10 10:31:32', 1, 1, NULL),
(102, 17, 20, 'Tudo bem também', '2025-11-10 10:31:47', 1, 1, NULL),
(103, 17, 20, 'Vi sua publicação do Fred e eu tenho uma Pug fêmea e estou buscando um par', '2025-11-10 10:32:23', 1, 1, NULL),
(104, 20, 17, 'Ótimo, pode me mandar uma foto dela?', '2025-11-10 10:33:36', 1, 1, NULL),
(105, 17, 20, 'Aqui está, o nome dela é Nina', '2025-11-10 10:33:51', 1, 1, 'uploads/mensagens/msg_6911e9bf766d0.jpg'),
(107, 17, 20, 'Vamos marcar de realizar esse encontro!', '2025-11-10 10:34:31', 1, 1, NULL),
(108, 20, 17, 'Vamos, assim que possível', '2025-11-10 10:34:54', 1, 1, NULL),
(111, 21, 20, 'opa', '2025-11-10 18:45:01', 1, 1, NULL),
(112, 21, 20, 'bao?', '2025-11-10 18:45:12', 1, 1, NULL),
(113, 24, 21, 'Opa', '2025-11-10 18:47:20', 1, 1, NULL),
(114, 24, 21, 'Opa', '2025-11-10 18:47:32', 1, 1, NULL),
(115, 24, 21, 'Opa', '2025-11-10 18:51:45', 1, 1, NULL),
(116, 21, 24, 'opa', '2025-11-10 18:52:12', 0, 0, NULL),
(117, 20, 21, 'Opa, tudo certo e você ?', '2025-11-10 22:27:23', 0, 0, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `posts`
--

CREATE TABLE `posts` (
  `idpost` int(11) NOT NULL,
  `conteudo` text DEFAULT NULL,
  `foto` varchar(45) DEFAULT NULL,
  `data_criacao` timestamp NULL DEFAULT NULL,
  `idusuario` varchar(45) DEFAULT NULL,
  `idcachorro` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `posts`
--

INSERT INTO `posts` (`idpost`, `conteudo`, `foto`, `data_criacao`, `idusuario`, `idcachorro`) VALUES
(17, 'Nina procurando seu namorado 😜😝', 'file_690ccfa6d74840.78258868.jpg', '2025-11-06 21:41:10', '17', '19'),
(18, 'Bernardo em seu descanso😴😴', 'file_690cd12e43ed85.27013824.jfif', '2025-11-06 21:47:42', '17', '21'),
(19, '😍😍', 'file_690cd479e745f8.86277625.jpg', '2025-11-06 22:01:45', '21', '22'),
(20, '🙄🙄', 'file_690cd57eb7c218.91198958.jpg', '2025-11-06 22:06:06', '19', '23'),
(21, '🙄🙄', 'file_690cd641e2e402.64567347.jpg', '2025-11-06 22:09:21', '19', '24'),
(22, 'Esfomeado 🤣🤣', 'file_690cd6e9eae638.27149507.jpg', '2025-11-06 22:12:09', '18', '26'),
(24, 'Bernardo passeando com sua Pandora ', 'file_690d2e0d46a9b5.59266227.jpg', '2025-11-07 04:23:57', '21', '22'),
(27, 'Estou buscando uma fêmea para o Fred', 'file_6911e8b27d1481.16424772.jpg', '2025-11-10 18:29:22', '20', '31');

-- --------------------------------------------------------

--
-- Estrutura para tabela `racas`
--

CREATE TABLE `racas` (
  `idraca` int(11) NOT NULL,
  `nome` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `racas`
--

INSERT INTO `racas` (`idraca`, `nome`) VALUES
(1, 'Labrador Retriever'),
(2, 'Pastor Alemão'),
(3, 'Bulldog Francês'),
(4, 'Poodle'),
(5, 'Golden Retriever'),
(6, 'Shih Tzu'),
(7, 'Boxer'),
(8, 'Dachshund'),
(9, 'Rottweiler'),
(10, 'Yorkshire Terrier'),
(11, 'Chihuahua'),
(12, 'Beagle'),
(13, 'Border Collie'),
(14, 'Doberman'),
(15, 'Cocker Spaniel'),
(16, 'Pinscher Miniatura'),
(17, 'Husky Siberiano'),
(18, 'Lhasa Apso'),
(19, 'Maltês'),
(20, 'Pit Bull'),
(21, 'Akita Inu'),
(22, 'Buldog Inglês'),
(23, 'Bichon Frisé'),
(24, 'Australian Cattle Dog'),
(25, 'Boston Terrier'),
(26, 'Weimaraner'),
(27, 'Schnauzer Miniatura'),
(28, 'Cane Corso'),
(29, 'Bull Terrier'),
(30, 'Whippet'),
(31, 'Samoyeda'),
(32, 'Shar Pei'),
(33, 'Pug'),
(34, 'Scottish Terrier'),
(35, 'Collie'),
(36, 'Pointer Inglês'),
(37, 'Setter Irlandês'),
(38, 'Bloodhound'),
(39, 'Basenji'),
(40, 'Papillon'),
(41, 'Spitz Alemão'),
(42, 'Cavalier King Charles Spaniel'),
(43, 'Chow Chow'),
(44, 'Presa Canario'),
(45, 'Dogo Argentino'),
(46, 'Fox Terrier'),
(47, 'Terrier Brasileiro'),
(48, 'Galgo Italiano'),
(49, 'Leonberger'),
(50, 'Komondor'),
(51, 'Kuvasz'),
(52, 'Cão de Crista Chinês'),
(53, 'Cão de Água Português'),
(54, 'Cão de Montanha dos Pirineus'),
(55, 'Cão de São Bernardo'),
(56, 'Mastim Tibetano'),
(57, 'Mastim Napolitano'),
(58, 'Airedale Terrier'),
(59, 'Basset Hound'),
(60, 'Cão de Fila de São Miguel'),
(61, 'Cão Lobo Tchecoslovaco'),
(62, 'Braco Alemão'),
(63, 'Braco Italiano'),
(64, 'Coton de Tulear'),
(65, 'Eurasier'),
(66, 'Fila Brasileiro'),
(67, 'Keeshond'),
(68, 'Manchester Terrier'),
(69, 'Norfolk Terrier'),
(70, 'Norwich Terrier'),
(71, 'Nova Scotia Duck Tolling Retriever'),
(72, 'Old English Sheepdog'),
(73, 'Otterhound'),
(74, 'Pequinês'),
(75, 'Puli'),
(76, 'Retriever da Baía de Chesapeake'),
(77, 'Retriever de Pelo Encaracolado'),
(78, 'Retriever de Flat Coated'),
(79, 'Saluki'),
(80, 'Schipperke'),
(81, 'Sealyham Terrier'),
(82, 'Setter Gordon'),
(83, 'Silky Terrier'),
(84, 'Skye Terrier'),
(85, 'Spaniel Tibetano'),
(86, 'Spinone Italiano'),
(87, 'Springer Spaniel Inglês'),
(88, 'Staffordshire Bull Terrier'),
(89, 'Sussex Spaniel'),
(90, 'Terrier Irlandês'),
(91, 'Terrier Tibetano'),
(92, 'Toy Fox Terrier'),
(93, 'Vizsla'),
(94, 'Welsh Corgi Pembroke'),
(95, 'Welsh Corgi Cardigan'),
(96, 'Welsh Springer Spaniel'),
(97, 'West Highland White Terrier'),
(98, 'Wolfhound Irlandês'),
(99, 'Xoloitzcuintli'),
(100, 'Yorkshire Terrier Biewer'),
(101, 'American Bully'),
(102, 'American Eskimo Dog'),
(103, 'American Staffordshire Terrier'),
(104, 'Belgian Malinois'),
(105, 'Belgian Tervuren'),
(106, 'Belgian Laekenois'),
(107, 'Belgian Groenendael');

-- --------------------------------------------------------

--
-- Estrutura para tabela `seguidos`
--

CREATE TABLE `seguidos` (
  `idseguido` int(11) NOT NULL,
  `idusuario` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `seguidos`
--

INSERT INTO `seguidos` (`idseguido`, `idusuario`) VALUES
(17, '18'),
(17, '19'),
(17, '20'),
(17, '21'),
(17, '23'),
(17, '35'),
(17, '37'),
(18, '17'),
(18, '19'),
(18, '20'),
(18, '21'),
(18, '23'),
(18, '35'),
(18, '38'),
(19, '18'),
(19, '20'),
(19, '21'),
(19, '23'),
(19, '35'),
(20, '17'),
(20, '18'),
(20, '19'),
(20, '21'),
(20, '23'),
(20, '35'),
(20, '36'),
(20, '37'),
(21, '17'),
(21, '18'),
(21, '19'),
(21, '20'),
(21, '23'),
(21, '35'),
(22, '18'),
(22, '19'),
(22, '20'),
(22, '23'),
(22, '35'),
(23, '17'),
(23, '18'),
(23, '21'),
(23, '35'),
(24, '17'),
(24, '18'),
(24, '21'),
(24, '23'),
(25, '17'),
(25, '18'),
(25, '21'),
(25, '23'),
(28, '23'),
(29, '17'),
(29, '36'),
(30, '23'),
(31, '23'),
(31, '37'),
(32, '22'),
(34, '37'),
(35, '23'),
(35, '37'),
(36, '23'),
(36, '37'),
(38, '18');

-- --------------------------------------------------------

--
-- Estrutura para tabela `sexos`
--

CREATE TABLE `sexos` (
  `idsexo` int(11) NOT NULL,
  `sexo` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `sexos`
--

INSERT INTO `sexos` (`idsexo`, `sexo`) VALUES
(1, 'Macho'),
(2, 'Fêmea');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `idusuario` int(11) NOT NULL,
  `nome` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `senha` varchar(45) DEFAULT NULL,
  `localizacao` varchar(45) DEFAULT NULL,
  `foto` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`idusuario`, `nome`, `email`, `senha`, `localizacao`, `foto`) VALUES
(17, 'André Nascimento', 'andrenascimento@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Videira - SC', 'file_690cce0c665273.66821418.jpg'),
(18, 'Bruno Falchetti', 'bruno@gmail.com', '698d51a19d8a121ce581499d7b701668', 'Videira', 'file_690cce3b2c4779.97963297.jpg'),
(19, 'Daniel Marcon', 'Daniel@gmail.com', '698d51a19d8a121ce581499d7b701668', 'Videira - SC', 'file_690cd2bccd5901.79710433.jpg'),
(20, 'Nicolas Pereti', 'nicolaspereti@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Videira - SC', 'file_690cd524d9e7f7.96739925.jpg'),
(21, 'Bernardo Weiss', 'bernardo@gmail.com', '698d51a19d8a121ce581499d7b701668', 'Videira', 'file_690cd3e70b2b52.16502909.jpg'),
(22, 'Carlos Oliveira', 'carlosoliveira@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Ibiam - SC', 'file_690cd4b0a17dc3.22275547.png'),
(23, 'André Kolett', 'andrekolett@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Tangará - SC', 'file_690cd61e3c19d9.32709724.png'),
(24, 'Frederico Fantin', 'fredericofantin@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Pato Branco - PR', 'file_690d0710a061f0.16135697.jpg'),
(25, 'Guilherme ', 'guilhermeifc2008@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Rda City', 'file_690ce57baaf902.59876132.jpg'),
(29, 'amanda morisca', 'amndmorsc07@gmail.com', '4a57ff78c6fc3e5edd1e17e45ddd12e1', 'Em casa', 'file_690cf2cc0cd433.18995586.png'),
(30, 'Donimar', 'donimaraloncio106@gmail.com', '59613b138eff16e17a876b677f8d8525', 'Salto veloso', 'file_690cfb890e54d7.06290577.jpg'),
(38, 'Chai', 'chailinda@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'videira', 'file_690fdb8b6583a9.64634828.jpeg');

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `cachorros`
--
ALTER TABLE `cachorros`
  ADD PRIMARY KEY (`idcachorro`);

--
-- Índices de tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`idcomentario`),
  ADD KEY `idpost` (`idpost`),
  ADD KEY `idusuario` (`idusuario`);

--
-- Índices de tabela `curtidas`
--
ALTER TABLE `curtidas`
  ADD PRIMARY KEY (`idcurtida`),
  ADD UNIQUE KEY `unique_curtida` (`idpost`,`idusuario`),
  ADD KEY `idusuario` (`idusuario`);

--
-- Índices de tabela `mensagens`
--
ALTER TABLE `mensagens`
  ADD PRIMARY KEY (`idmensagem`),
  ADD KEY `idx_remetente` (`idremetente`),
  ADD KEY `idx_destinatario` (`iddestinatario`),
  ADD KEY `idx_data_envio` (`data_envio`);

--
-- Índices de tabela `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`idpost`);

--
-- Índices de tabela `racas`
--
ALTER TABLE `racas`
  ADD PRIMARY KEY (`idraca`);

--
-- Índices de tabela `seguidos`
--
ALTER TABLE `seguidos`
  ADD PRIMARY KEY (`idseguido`,`idusuario`);

--
-- Índices de tabela `sexos`
--
ALTER TABLE `sexos`
  ADD PRIMARY KEY (`idsexo`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idusuario`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `cachorros`
--
ALTER TABLE `cachorros`
  MODIFY `idcachorro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de tabela `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `idcomentario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `curtidas`
--
ALTER TABLE `curtidas`
  MODIFY `idcurtida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de tabela `mensagens`
--
ALTER TABLE `mensagens`
  MODIFY `idmensagem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT de tabela `posts`
--
ALTER TABLE `posts`
  MODIFY `idpost` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de tabela `racas`
--
ALTER TABLE `racas`
  MODIFY `idraca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT de tabela `sexos`
--
ALTER TABLE `sexos`
  MODIFY `idsexo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Restrições para dumps de tabelas
--

--
-- Restrições para tabelas `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`idpost`) REFERENCES `posts` (`idpost`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`idusuario`) ON DELETE CASCADE;

--
-- Restrições para tabelas `curtidas`
--
ALTER TABLE `curtidas`
  ADD CONSTRAINT `curtidas_ibfk_1` FOREIGN KEY (`idpost`) REFERENCES `posts` (`idpost`) ON DELETE CASCADE,
  ADD CONSTRAINT `curtidas_ibfk_2` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`idusuario`) ON DELETE CASCADE;

--
-- Restrições para tabelas `mensagens`
--
ALTER TABLE `mensagens`
  ADD CONSTRAINT `mensagens_ibfk_1` FOREIGN KEY (`idremetente`) REFERENCES `usuarios` (`idusuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `mensagens_ibfk_2` FOREIGN KEY (`iddestinatario`) REFERENCES `usuarios` (`idusuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
