-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-04-2024 a las 11:56:28
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
-- Base de datos: `practica3`
--

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`id`) VALUES
(1);

--
-- Volcado de datos para la tabla `ambitos`
--

INSERT INTO `ambitos` (`id`, `nombre`) VALUES
(1, 'Tecnología'),
(2, 'Salud'),
(3, 'Turismo'),
(4, 'Educación'),
(5, 'Galletas'),
(6, 'Enseñanza'),
(8, 'Musica'),
(9, 'Programador');

--  
-- Volcado de datos para la tabla `anuncios`
--

INSERT INTO `anuncios` (`id`, `titulo`, `descripcion`, `categoria`, `contacto`, `fecha_publicacion`, `idAutor`) VALUES
(1, 'Prueba', 'Prueba', '3', 'Paco', '2024-04-09 16:16:48', 11),
(2, 'HolaQTal', 'Yo me llamo Ralph', '3', 'Paco', '2024-04-09 20:47:34', 11),
(3, 'MentaFuerte', 'MentaFuerte', '3', 'Paco', '2024-04-09 20:53:15', 11);

--
-- Volcado de datos para la tabla `comunidades`
--

INSERT INTO `comunidades` (`id`, `nombre`) VALUES
(1, 'Andalucía'),
(2, 'Aragón'),
(3, 'Islas Baleares'),
(4, 'Canarias'),
(5, 'Cantabria'),
(6, 'Castilla-La Mancha'),
(7, 'Castilla y León'),
(8, 'Cataluña'),
(9, 'Comunidad de Madrid'),
(10, 'Comunidad Foral de Navarra'),
(11, 'Comunidad Valenciana'),
(12, 'Extremadura'),
(13, 'Galicia'),
(14, 'País Vasco'),
(15, 'Principado de Asturias'),
(16, 'Región de Murcia'),
(17, 'La Rioja');

--
-- Volcado de datos para la tabla `contratos`
--

INSERT INTO `contratos` (`id`, `idEmpresa`, `idPueblo`, `duracion`, `terminos`) VALUES
(16, 12, 11, 56, 'a'),
(17, 12, 11, 345, 'Hola mundo\r\n'),
(18, 17, 14, 12, 'aaaaaaaaaa'),
(19, 17, 11, 1, 'jeje');

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`id`, `nTrabajadores`, `ambito`) VALUES
(12, 34, 5),
(15, 1, 6),
(17, 5445, 1),
(18, 89, 8),
(22, 1, 1),
(23, 9, 9),
(24, 1, 1);

--
-- Volcado de datos para la tabla `pueblos`
--

INSERT INTO `pueblos` (`id`, `cif`, `comunidad`) VALUES
(11, 56, 17),
(13, 8765, 5),
(14, 12, 3),
(16, 99, 1),
(21, 1, 3);

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(1, 'Admin'),
(2, 'Empresa'),
(3, 'Pueblo');

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `idPueblo`, `idAmbito`, `cantidad`) VALUES
(11, 11, 5, 2),
(12, 14, 1, 1),
(13, 11, 1, 1);

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombreUsuario`, `password`, `nombre`, `rol`) VALUES
(1, 'admin', '$2y$10$O3c1kBFa2yDK5F47IUqusOJmIANjHP6EiPyke5dD18ldJEow.e0eS', 'admin', 1),
(11, 'Pueblo1', '$2y$10$1svH3MShYO9zAfJlk3lPVOnx2D7yME9UAJHG6NjR4xlzclZmg3yte', 'Pueblo1', 3),
(12, 'Empresa1', '$2y$10$O1ZRyausYqBtU5pDzat4H.dHDLB5n/JuLXG4z345vlvgZbHn.EROy', 'Empresa1', 2),
(13, 'Pueblo2', '$2y$10$B2jZ30wanna8Xqu/U7Y65OitPduMRQLVVLPIF3xpCyyIMnK.0gsoq', 'Pueblo2', 3),
(14, 'Pueblo3', '$2y$10$rlC14MeSOhHo9vNiYB2jEesBy4AJYaXXoCzaTiM1D/1296t9is8pe', 'Pueblo3', 3),
(15, 'Empresa2', '$2y$10$aibhyMztF4uLMa/.vVlbMeHVSMv4LLDiCUzzq8OW5ncRoevaltOj2', 'Empresa2', 2),
(16, 'Pueblo4', '$2y$10$Xct.G2MeEfJAteKPIe2zzu2lrGIDToElUwZsFbxzZ.I.g3lBbz/Wy', 'Pueblo4', 3),
(17, 'Empresa3', '$2y$10$P/YFj8NIXnK8P3ZACgJiIev/YhFvhDUrbq4U04r34inhQds43sXXq', 'Empresa3', 2),
(18, 'Empresa4', '$2y$10$f4.aBZ6Tje19La55q0reeeG6NY2R44iO4JMtUWkPkOtFA/MewloBa', 'Empresa4', 2),
(19, 'Pueblo5', '$2y$10$XWxLBrXySw61bs5YQdFFQebjhOIWNUm0SJwUUJdj4sO1GtbHnGiAe', 'Pueblo5', 3),
(20, 'admin2', '$2y$10$AMtJp8ZKSVMFu/BV/.FLQOYakMAmgRilpWfOKvMTPG9yrtDqU5S.O', 'admin2', 1),
(21, 'Pueblo6', '$2y$10$vDb3WKvppTPcW5H.lL3.weu2UF0uSUbBgJpJ9GR1hHBAlA9MEPaj6', 'Pueblo6', 3),
(22, 'Empresa5', '$2y$10$JoS7BJHNPeU4h8pw4CubWu6gO0L2w5n.GHUbD/3akuqTIReMUAYfW', 'Empresa5', 2),
(23, 'Empresa6', '$2y$10$MTPFBu22FDK1Q5tdrQGfFOG4Bpj0Ce9RJgyVAJaK50oRdi5eqOdf6', 'Empresa6', 2),
(24, 'Empresa7', '$2y$10$yFGNTNNGm/PEeIKLz4ZWhOYFqj2MU.TAvpnP.PoUHtULLCkjpTSV6', 'Empresa7', 2);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
