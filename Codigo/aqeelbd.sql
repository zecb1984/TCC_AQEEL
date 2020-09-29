-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 187.45.196.150
-- Generation Time: 29-Set-2020 às 11:06
-- Versão do servidor: 5.7.17-13-log
-- PHP Version: 5.6.40-0+deb8u12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aqeelbd`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `aula`
--

CREATE TABLE `aula` (
  `id_aula` int(11) NOT NULL,
  `nomeAula` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `nomeInstrutor_0` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `horarioInicio` timestamp NOT NULL,
  `horarioFim` timestamp NOT NULL,
  `diasDaSemana` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `cpfInstrutor` varchar(11) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `aula`
--

INSERT INTO `aula` (`id_aula`, `nomeAula`, `nomeInstrutor_0`, `horarioInicio`, `horarioFim`, `diasDaSemana`, `cpfInstrutor`) VALUES
(31, 'abdominal', 'tiÃ§Ã£o', '2000-01-01 22:00:00', '2000-01-01 23:00:00', ' 4 ', '11111111111'),
(33, 'ginÃ¡stica ', 'givan', '2000-01-01 11:00:00', '2000-01-01 11:30:00', ' 2  3  4 ', '34568912356'),
(32, 'musculaÃ§Ã£o', 'givan', '2000-01-01 11:00:00', '2000-01-01 12:00:00', ' 1  5 ', '34568912356'),
(17, 'shape', 'tiÃ§Ã£o', '2000-01-01 23:00:00', '2000-01-01 23:30:00', ' 4 ', '11111111111'),
(34, 'super pulo', 'givano', '2000-01-01 17:00:00', '2000-01-01 17:30:00', ' 3  5 ', '56086378878');

-- --------------------------------------------------------

--
-- Estrutura da tabela `avaliacao`
--

CREATE TABLE `avaliacao` (
  `id_avaliacao` int(11) NOT NULL,
  `cpfCliente` varchar(11) COLLATE latin1_general_ci NOT NULL,
  `anamnesePaciente` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `exameDobrasCutaneas` varchar(10) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `exameErgometrico` varchar(10) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `avaliacao`
--

INSERT INTO `avaliacao` (`id_avaliacao`, `cpfCliente`, `anamnesePaciente`, `exameDobrasCutaneas`, `exameErgometrico`) VALUES
(7, '27924332804', 'apto', '20', 'apto'),
(8, '56086378878', 'blablabla', 'blablabla', 'blablabla'),
(9, '12345678912', 'blablabla', 'blablabla', 'blablabla'),
(11, '30911084819', 'blablabla', 'blablabla', 'blablabla'),
(12, '34567890123', 'blablabla', 'blablabla', 'blablabla'),
(13, '11111111111', 'apto', '10', 'apto'),
(14, '33333333333', 'inapta', '10', 'inapta');

-- --------------------------------------------------------

--
-- Estrutura da tabela `cliente`
--

CREATE TABLE `cliente` (
  `cpfCliente` varchar(11) COLLATE latin1_general_ci NOT NULL,
  `nomeCliente` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `identidadeCliente` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `enderecoCliente` varchar(50) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `cliente`
--

INSERT INTO `cliente` (`cpfCliente`, `nomeCliente`, `identidadeCliente`, `enderecoCliente`) VALUES
('11111111111', 'Carlos', '123456789', 'rua dois'),
('12212121212', 'carlos pÃ©ricles baldoino costa', '212121212', 'rua amÃ©rica latina, 451 - pg'),
('12345678912', 'ezequiel', '452678970', 'tamanhomax'),
('27924332804', 'mario', '123456789', 'rua um'),
('30911084819', 'ulisses', '12345678', 'rua de deu'),
('33111957870', 'diogo Ã¡lva', '326413972', 'rua antÃ³ni'),
('33333333333', 'kÃ¡tia', '123123123', 'rua marÃ§o'),
('34567890123', 'ezequiel de lima barbosa', '234567895', 'rua alecrim'),
('55555555555', 'Ã¢ngelÃ£o', '523654236', 'rua anjÃ£o'),
('56086378878', 'laura', '45267897', 'rua alecri');

-- --------------------------------------------------------

--
-- Estrutura da tabela `ferias`
--

CREATE TABLE `ferias` (
  `cpfCliente` varchar(11) COLLATE latin1_general_ci NOT NULL,
  `dataFerias` date NOT NULL,
  `duracaoFerias` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `ferias`
--

INSERT INTO `ferias` (`cpfCliente`, `dataFerias`, `duracaoFerias`) VALUES
('33333333333', '2022-03-25', 10),
('27924332804', '2021-01-13', 10),
('27924332804', '2021-01-24', 10),
('27924332804', '2021-02-05', 10),
('33111957870', '2020-12-01', 10),
('33111957870', '2021-02-01', 10),
('33111957870', '2021-03-01', 10),
('12212121212', '2020-09-27', 10),
('30911084819', '2020-03-03', 10),
('30911084819', '2020-04-03', 20);

-- --------------------------------------------------------

--
-- Estrutura da tabela `instrutor`
--

CREATE TABLE `instrutor` (
  `cpfInstrutor` varchar(11) COLLATE latin1_general_ci NOT NULL,
  `nomeInstrutor` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `identidadeInstrutor` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `tipoAtividade` varchar(30) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `instrutor`
--

INSERT INTO `instrutor` (`cpfInstrutor`, `nomeInstrutor`, `identidadeInstrutor`, `tipoAtividade`) VALUES
('25874136952', 'aÃ§Ã£Ã³bÃª', '321654987', 'body pump'),
('30911084819', 'ezequiel de lima barbosa', '234567895', 'super pulo'),
('34568912356', 'givan', '234567895', 'body jump'),
('56086378878', 'givano', '45267897', 'super pulo'),
('01234567890', 'maurÃ­cio serra', '121212121', 'musculaÃ§Ã£o localizad'),
('11111111111', 'tiÃ§Ã£o', '874651236', 'aÃ§Ã£o');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pagamento`
--

CREATE TABLE `pagamento` (
  `cpfCliente` varchar(11) COLLATE latin1_general_ci NOT NULL,
  `plano` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `dataPagamento` date NOT NULL,
  `situacaoPagamento` char(1) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `pagamento`
--

INSERT INTO `pagamento` (`cpfCliente`, `plano`, `dataPagamento`, `situacaoPagamento`) VALUES
('33333333333', '2', '2021-12-31', '1'),
('12345678912', '1', '2020-02-25', '1'),
('11111111111', '1', '2020-10-25', '1'),
('11111111111', '2', '2021-12-23', '2'),
('27924332804', '2', '2021-01-12', '1'),
('12345678912', '1', '2020-03-30', '2'),
('33111957870', '2', '2020-09-14', '1'),
('34567890123', '2', '2020-02-25', '1'),
('55555555555', '1', '2020-02-25', '2'),
('12212121212', '2', '2020-09-26', '1'),
('30911084819', '2', '2020-02-25', '1'),
('56086378878', '1', '2020-02-25', '1');

-- --------------------------------------------------------

--
-- Estrutura da tabela `presenca`
--

CREATE TABLE `presenca` (
  `cpfCliente` varchar(11) COLLATE latin1_general_ci NOT NULL,
  `nomeInstrutor_0` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `nomeAula` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `id_aula` int(11) NOT NULL,
  `id_presenca` int(11) NOT NULL,
  `diaSemana` varchar(10) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `presenca`
--

INSERT INTO `presenca` (`cpfCliente`, `nomeInstrutor_0`, `nomeAula`, `id_aula`, `id_presenca`, `diaSemana`) VALUES
('27924332804', 'tiÃ§Ã£o', 'shape', 17, 14, ''),
('27924332804', 'givan', 'ginÃ¡stica ', 33, 15, ''),
('27924332804', 'givan', 'ginÃ¡stica ', 33, 16, ''),
('27924332804', 'givan', 'ginÃ¡stica ', 33, 17, ''),
('27924332804', 'tiÃ§Ã£o', 'abdominal', 31, 18, ''),
('27924332804', 'tiÃ§Ã£o', 'shape', 17, 19, ''),
('27924332804', 'tiÃ§Ã£o', 'shape', 17, 20, ''),
('27924332804', 'givan', 'ginÃ¡stica ', 33, 21, ''),
('27924332804', 'tiÃ§Ã£o', 'abdominal', 31, 22, ''),
('27924332804', 'givan', 'ginÃ¡stica ', 33, 23, ''),
('30911084819', 'givan', 'ginÃ¡stica ', 33, 24, ''),
('30911084819', 'givan', 'ginÃ¡stica ', 33, 25, ''),
('11111111111', 'givano', 'super pulo', 34, 26, ''),
('30911084819', 'givano', 'super pulo', 34, 27, ''),
('27924332804', 'givano', 'super pulo', 34, 29, ''),
('11111111111', 'givan', 'musculaÃ§Ã£o', 32, 31, '5'),
('11111111111', 'givan', 'musculaÃ§Ã£o', 32, 33, '1'),
('27924332804', 'givan', 'musculaÃ§Ã£o', 32, 34, '1'),
('27924332804', 'givan', 'musculaÃ§Ã£o', 32, 35, '5'),
('11111111111', 'givano', 'super pulo', 34, 36, '3'),
('55555555555', 'givano', 'super pulo', 34, 37, '3');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aula`
--
ALTER TABLE `aula`
  ADD PRIMARY KEY (`nomeAula`),
  ADD UNIQUE KEY `id_aula` (`id_aula`),
  ADD KEY `instrutorfk` (`nomeInstrutor_0`);

--
-- Indexes for table `avaliacao`
--
ALTER TABLE `avaliacao`
  ADD UNIQUE KEY `id_avaliacao` (`id_avaliacao`) USING BTREE,
  ADD KEY `clienteavaliacaofk` (`cpfCliente`);

--
-- Indexes for table `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`cpfCliente`);

--
-- Indexes for table `ferias`
--
ALTER TABLE `ferias`
  ADD KEY `clienteferiasfk` (`cpfCliente`);

--
-- Indexes for table `instrutor`
--
ALTER TABLE `instrutor`
  ADD PRIMARY KEY (`nomeInstrutor`);

--
-- Indexes for table `pagamento`
--
ALTER TABLE `pagamento`
  ADD KEY `clientepagamentofk` (`cpfCliente`);

--
-- Indexes for table `presenca`
--
ALTER TABLE `presenca`
  ADD UNIQUE KEY `id_presenca_UNIQUE` (`id_presenca`),
  ADD KEY `clienteaulafk` (`cpfCliente`),
  ADD KEY `aulaidfk` (`id_aula`),
  ADD KEY `aulafk` (`nomeAula`),
  ADD KEY `instrutorpresencafk` (`nomeInstrutor_0`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aula`
--
ALTER TABLE `aula`
  MODIFY `id_aula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `avaliacao`
--
ALTER TABLE `avaliacao`
  MODIFY `id_avaliacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `presenca`
--
ALTER TABLE `presenca`
  MODIFY `id_presenca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `aula`
--
ALTER TABLE `aula`
  ADD CONSTRAINT `instrutorfk` FOREIGN KEY (`nomeInstrutor_0`) REFERENCES `instrutor` (`nomeInstrutor`);

--
-- Limitadores para a tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  ADD CONSTRAINT `clienteavaliacaofk` FOREIGN KEY (`cpfCliente`) REFERENCES `cliente` (`cpfCliente`);

--
-- Limitadores para a tabela `ferias`
--
ALTER TABLE `ferias`
  ADD CONSTRAINT `clienteferiasfk` FOREIGN KEY (`cpfCliente`) REFERENCES `cliente` (`cpfCliente`);

--
-- Limitadores para a tabela `pagamento`
--
ALTER TABLE `pagamento`
  ADD CONSTRAINT `clientepagamentofk` FOREIGN KEY (`cpfCliente`) REFERENCES `cliente` (`cpfCliente`);

--
-- Limitadores para a tabela `presenca`
--
ALTER TABLE `presenca`
  ADD CONSTRAINT `aulafk` FOREIGN KEY (`nomeAula`) REFERENCES `aula` (`nomeAula`),
  ADD CONSTRAINT `clienteaulafk` FOREIGN KEY (`cpfCliente`) REFERENCES `cliente` (`cpfCliente`),
  ADD CONSTRAINT `idaulafk` FOREIGN KEY (`id_aula`) REFERENCES `aula` (`id_aula`),
  ADD CONSTRAINT `instrutorpresencafk` FOREIGN KEY (`nomeInstrutor_0`) REFERENCES `instrutor` (`nomeInstrutor`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
