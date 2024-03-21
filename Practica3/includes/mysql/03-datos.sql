-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-03-2024 a las 18:22:36
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Truncar la tabla roles
TRUNCATE TABLE roles;

-- Truncar la tabla comunidades
TRUNCATE TABLE comunidades;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `practica3`
--

--
-- Volcado de datos para la tabla `ambitos`
--

INSERT INTO `ambitos` (`id`, `nombre`) VALUES
(1, 'Tecnología'),
(2, 'Salud'),
(3, 'Turismo'),
(4, 'Educación'),
(5, 'Galletas');

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
(1, 2, 3, 30, 'Condiciones de contrato 1'),
(2, 4, 5, 60, 'Condiciones de contrato 2'),
(3, 6, 7, 90, 'Condiciones de contrato 3'),
(4, 4, 7, 4, 'Condiciones de contrato 4'),
(5, 8, 3, 30, 'Condiciones de contrato 5');

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`id`, `nTrabajadores`, `ambito`) VALUES
(2, 50, 1),
(4, 30, 2),
(6, 20, 3),
(8, 88, 5);

--
-- Volcado de datos para la tabla `pueblos`
--

INSERT INTO `pueblos` (`id`, `cif`, `comunidad`) VALUES
(3, 123456, 1),
(5, 234567, 7),
(7, 345678, 9);

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
(1, 7, 2, 1),
(2, 3, 1, 1),
(3, 5, 2, 1),
(4, 7, 3, 1),
(5, 3, 5, 1);

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombreUsuario`, `password`, `nombre`, `rol`) VALUES
(1, 'admin', 'adminpass', 'admin', 1),
(2, 'empresa1', 'empresapass', 'empresa1', 2),
(3, 'pueblo1', 'pueblopass', 'pueblo1', 3),
(4, 'empresa2', 'empresapass', 'Empresa 2', 2),
(5, 'pueblo2', 'pueblopass', 'pueblo2', 3),
(6, 'empresa3', 'empresapass', 'empresa3', 2),
(7, 'pueblo3', 'pueblopass', 'pueblo3', 3),
(8, 'empresa4', 'empresapass', 'empresa4', 2);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;