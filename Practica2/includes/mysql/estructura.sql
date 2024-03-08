-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-03-2024 a las 10:58:33
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `practica2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `ID` int(11) NOT NULL,
  `permisos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chat`
--

CREATE TABLE `chat` (
  `ID` int(11) NOT NULL,
  `mensajes` longtext NOT NULL,
  `integranteEmpresa` int(11) NOT NULL,
  `integrantePueblo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contrato`
--

CREATE TABLE `contrato` (
  `ID` int(11) NOT NULL,
  `tiempo` date NOT NULL,
  `terminos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`terminos`)),
  `pueblo` int(11) NOT NULL,
  `empresa` int(11) NOT NULL,
  `admin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cooperativa`
--

CREATE TABLE `cooperativa` (
  `ID` int(11) NOT NULL,
  `integrantes` int(11) NOT NULL,
  `contratos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `ID` int(11) NOT NULL,
  `nombre` varchar(35) NOT NULL,
  `nTrabajadores` int(11) NOT NULL,
  `ambito` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`ambito`)),
  `contratos` int(11) NOT NULL,
  `cooperativas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticia`
--

CREATE TABLE `noticia` (
  `ID` int(11) NOT NULL,
  `descripcion` longtext NOT NULL,
  `autor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pueblo`
--

CREATE TABLE `pueblo` (
  `ID` int(11) NOT NULL,
  `c.a.` enum('Andalucía','Aragón','Principado de Asturias','Islas Baleares','Canarias','Cantabria','Castilla-La Mancha','Castilla y León','Cataluña','Comunidad Valenciana','Extremadura','Galicia','La Rioja','Comunidad de Madrid','Región de Murcia','Comunidad Foral de Navarra','País Vasco','Ceuta','Melilla') NOT NULL,
  `nombre` varchar(35) NOT NULL,
  `servicios` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`servicios`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `chatIntegrante1` (`integranteEmpresa`),
  ADD KEY `chatIntegrante2` (`integrantePueblo`) USING BTREE;

--
-- Indices de la tabla `contrato`
--
ALTER TABLE `contrato`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `contratoPuebloEmpresa` (`pueblo`,`empresa`),
  ADD KEY `empresa` (`empresa`),
  ADD KEY `contratoAdmin` (`admin`);

--
-- Indices de la tabla `cooperativa`
--
ALTER TABLE `cooperativa`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `coop` (`integrantes`,`contratos`),
  ADD KEY `contratos` (`contratos`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `empresaContratos` (`contratos`),
  ADD KEY `empresaCooperativas` (`cooperativas`);

--
-- Indices de la tabla `noticia`
--
ALTER TABLE `noticia`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `autorNoticia` (`autor`);

--
-- Indices de la tabla `pueblo`
--
ALTER TABLE `pueblo`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `serviciosPueblos` (`servicios`(768));

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin`
--
ALTER TABLE `admin`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `chat`
--
ALTER TABLE `chat`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `contrato`
--
ALTER TABLE `contrato`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cooperativa`
--
ALTER TABLE `cooperativa`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `noticia`
--
ALTER TABLE `noticia`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pueblo`
--
ALTER TABLE `pueblo`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_2` FOREIGN KEY (`integrantePueblo`) REFERENCES `pueblo` (`ID`),
  ADD CONSTRAINT `chat_ibfk_3` FOREIGN KEY (`integranteEmpresa`) REFERENCES `empresa` (`ID`);

--
-- Filtros para la tabla `contrato`
--
ALTER TABLE `contrato`
  ADD CONSTRAINT `contrato_ibfk_1` FOREIGN KEY (`empresa`) REFERENCES `empresa` (`ID`),
  ADD CONSTRAINT `contrato_ibfk_2` FOREIGN KEY (`admin`) REFERENCES `admin` (`ID`);

--
-- Filtros para la tabla `cooperativa`
--
ALTER TABLE `cooperativa`
  ADD CONSTRAINT `cooperativa_ibfk_1` FOREIGN KEY (`integrantes`) REFERENCES `empresa` (`ID`),
  ADD CONSTRAINT `cooperativa_ibfk_2` FOREIGN KEY (`contratos`) REFERENCES `contrato` (`ID`),
  ADD CONSTRAINT `cooperativa_ibfk_3` FOREIGN KEY (`ID`) REFERENCES `empresa` (`cooperativas`);

--
-- Filtros para la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD CONSTRAINT `empresa_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `noticia` (`autor`),
  ADD CONSTRAINT `empresa_ibfk_2` FOREIGN KEY (`contratos`) REFERENCES `contrato` (`ID`);

--
-- Filtros para la tabla `noticia`
--
ALTER TABLE `noticia`
  ADD CONSTRAINT `noticia_ibfk_1` FOREIGN KEY (`autor`) REFERENCES `pueblo` (`ID`);

--
-- Filtros para la tabla `pueblo`
--
ALTER TABLE `pueblo`
  ADD CONSTRAINT `pueblo_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `contrato` (`pueblo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
