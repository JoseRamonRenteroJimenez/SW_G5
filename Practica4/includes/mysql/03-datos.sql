-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-04-2024 a las 10:40:57
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
-- Base de datos: `proyectosw`
--

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
