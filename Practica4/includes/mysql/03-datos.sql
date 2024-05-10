-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-05-2024 a las 03:05:58
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
-- Base de datos: `practica4`
--

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`id`) VALUES
(1),
(2);

--
-- Volcado de datos para la tabla `ambitos`
--

INSERT INTO `ambitos` (`id`, `nombre`) VALUES
(1, 'Informática');

--
-- Volcado de datos para la tabla `anuncios`
--

INSERT INTO `anuncios` (`id`, `titulo`, `descripcion`, `contacto`, `fecha_publicacion`, `idAutor`, `anuncioImg`) VALUES
(1, 'Nuevo anuncio user normal', 'Vendemos camisetas', 'Camiseta.com', '2024-05-10 02:57:16', 6, 'imagenes/anunciodefault.png'),
(2, 'Nueva actualización del sistema', 'Prueba anuncio sin foto', 'admin1', '2024-05-10 02:58:38', 1, 'imagenes/anunciodefault.png'),
(3, 'Nueva actualización del sistema', 'Anuncio con foto', 'Admin1', '2024-05-10 03:00:36', 1, 'uploads/663d71b43fe80.png');

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

INSERT INTO `contratos` (`id`, `idEmpresa`, `idPueblo`, `fechaInicial`, `fechaFinal`, `terminos`, `estado`) VALUES
(1, 5, 3, '2024-05-15', '2024-05-22', 'Nuevo contrato', 3),
(2, 5, 3, '2024-05-15', '2024-05-31', 'Nuevo contrato 2', 3),
(3, 5, 3, '2024-05-15', '2024-05-23', 'Nuevo contrato prueba servicios', 4);

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`id`, `nTrabajadores`, `ambito`) VALUES
(5, 12, 1),
(6, 56, 1);

--
-- Volcado de datos para la tabla `encargos`
--

INSERT INTO `encargos` (`id`, `idVecino`, `idEmpresa`, `fecha`, `terminos`, `estado`) VALUES
(1, 7, 6, '2024-05-10', 'Prueba encargo', 2);

--
-- Volcado de datos para la tabla `notificaciones`
--

INSERT INTO `notificaciones` (`id`, `idReferencia`, `tipo`, `fecha`, `estado`, `idEmisor`, `idReceptor`, `titulo`) VALUES
(1, 1, 1, '2024-05-10 02:34:46', 2, 5, 3, 'Nuevo Contrato Pendiente de Aprobación'),
(2, 1, 1, '2024-05-10 02:35:35', 2, 5, 3, 'Contrato Cancelado'),
(3, 1, 1, '2024-05-10 02:35:35', 2, 3, 5, 'Contrato Cancelado'),
(4, 2, 1, '2024-05-10 02:36:43', 2, 5, 3, 'Nuevo Contrato Pendiente de Aprobación'),
(5, 2, 1, '2024-05-10 02:37:40', 2, 3, 5, 'Contrato Aprobado'),
(6, 2, 1, '2024-05-10 02:38:09', 2, 5, 3, 'Contrato Modificado'),
(7, 2, 1, '2024-05-10 02:39:12', 2, 5, 3, 'Contrato Cancelado'),
(8, 2, 1, '2024-05-10 02:39:12', 2, 3, 5, 'Contrato Cancelado'),
(9, 3, 1, '2024-05-10 02:48:16', 1, 5, 3, 'Nuevo Contrato Pendiente de Aprobación'),
(10, 1, 2, '2024-05-10 02:51:56', 2, 7, 6, 'Nuevo Encargo Pendiente'),
(11, 1, 2, '2024-05-10 02:53:11', 2, 6, 7, 'Encargo Aceptado'),
(12, 1, 2, '2024-05-10 02:53:42', 2, 6, 7, 'Encargo Completado'),
(13, 1, 2, '2024-05-10 02:53:42', 2, 7, 6, 'Encargo Completado'),
(14, 2, 3, '2024-05-10 02:58:38', 1, 1, 2, 'Nuevo Anuncio: Nueva actualización del sistema'),
(15, 2, 3, '2024-05-10 02:58:38', 1, 1, 3, 'Nuevo Anuncio: Nueva actualización del sistema'),
(16, 2, 3, '2024-05-10 02:58:38', 1, 1, 4, 'Nuevo Anuncio: Nueva actualización del sistema'),
(17, 2, 3, '2024-05-10 02:58:38', 2, 1, 5, 'Nuevo Anuncio: Nueva actualización del sistema'),
(18, 2, 3, '2024-05-10 02:58:38', 1, 1, 6, 'Nuevo Anuncio: Nueva actualización del sistema'),
(19, 2, 3, '2024-05-10 02:58:38', 1, 1, 7, 'Nuevo Anuncio: Nueva actualización del sistema'),
(20, 2, 3, '2024-05-10 02:58:38', 1, 1, 8, 'Nuevo Anuncio: Nueva actualización del sistema'),
(21, 3, 3, '2024-05-10 03:00:36', 1, 1, 2, 'Nuevo Anuncio: Nueva actualización del sistema'),
(22, 3, 3, '2024-05-10 03:00:36', 1, 1, 3, 'Nuevo Anuncio: Nueva actualización del sistema'),
(23, 3, 3, '2024-05-10 03:00:36', 1, 1, 4, 'Nuevo Anuncio: Nueva actualización del sistema'),
(24, 3, 3, '2024-05-10 03:00:36', 1, 1, 5, 'Nuevo Anuncio: Nueva actualización del sistema'),
(25, 3, 3, '2024-05-10 03:00:36', 1, 1, 6, 'Nuevo Anuncio: Nueva actualización del sistema'),
(26, 3, 3, '2024-05-10 03:00:36', 1, 1, 7, 'Nuevo Anuncio: Nueva actualización del sistema'),
(27, 3, 3, '2024-05-10 03:00:36', 1, 1, 8, 'Nuevo Anuncio: Nueva actualización del sistema');

--
-- Volcado de datos para la tabla `pueblos`
--

INSERT INTO `pueblos` (`id`, `cif`, `comunidad`) VALUES
(3, 1, 1),
(4, 2, 2);

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombreUsuario`, `password`, `nombre`, `rol`, `nombreImg`) VALUES
(1, 'admin1', '$2y$10$/3MWznRb1dI9Qx7gri/w2ODrtuhE0WhTJDj2WiSqhYtpu81AgAZmW', 'admin1', 1, 'uploads/663d663ba2f5e.jpg'),
(2, 'admin2', '$2y$10$sXT/agZsaca/fxcmrLhRPuyWqrLYfKkWrboa2DqzBLTrsokkEGdiS', 'admin2', 1, 'imagenes/default.png'),
(3, 'pueblo1', '$2y$10$NZIad8EMYIZeLQOH6yF1me5AueVfhj8ha6mniZoM94gcZ315PaD9W', 'pueblo1', 3, 'uploads/663d6704429d4.jpg'),
(4, 'pueblo2', '$2y$10$myaZsu9M4pgCf5TYQ.Q2AOxo7ghRiUwmhptmU1DaLHw1QSfGH28o6', 'pueblo2', 3, 'imagenes/default.png'),
(5, 'empresa1', '$2y$10$efVDfr6r1kTB7xX5X4gFfOJxS9ycy.yU3nSB1kwkbQPJfeIjn6mS.', 'empresa1', 2, 'uploads/663d678079085.jpg'),
(6, 'empresa2', '$2y$10$wqkZd6vf3hpGK2ItclWdt.PsR0IaoDoleKp20SpHDhBwoaTa7DryG', 'empresa2', 2, 'imagenes/default.png'),
(7, 'vecino1', '$2y$10$/wS.CdKGkAPoCNWti32Mn.b8Rb2pxnyRWeLBsYycXr2WRnT/wNkom', 'vecino1', 4, 'uploads/663d6828640ac.png'),
(8, 'vecino2', '$2y$10$BeWcCVZoPdqEtXZEsVfffu7Nt6zUuGYt1/o.LNnzVcK5D9ZgTf3Vy', 'vecino2', 4, 'imagenes/default.png');

--
-- Volcado de datos para la tabla `vecinos`
--

INSERT INTO `vecinos` (`id`, `idPueblo`, `idEmpresa`) VALUES
(7, 3, 0),
(8, 4, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
