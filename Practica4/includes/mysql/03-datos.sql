-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-05-2024 a las 14:09:30
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
(3);

--
-- Volcado de datos para la tabla `ambitos`
--

INSERT INTO `ambitos` (`id`, `nombre`) VALUES
(1, 'Informática'),
(2, 'Construcción');

--
-- Volcado de datos para la tabla `anuncios`
--

INSERT INTO `anuncios` (`id`, `titulo`, `descripcion`, `contacto`, `fecha_publicacion`, `idAutor`, `anuncioImg`) VALUES
(1, 'La página está en funcionamiento', 'Ya podeis usarla.', 'El admin', '2024-05-10 12:22:44', 3, 'imagenes/anunciodefault.png'),
(2, 'Prueba noticia Pueblo 1', 'Se acerca el final de curso', 'Puedes llamar al 112', '2024-05-10 12:27:53', 1, 'uploads/663df6a940f6d.png');

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
(1, 2, 1, '2024-05-10', '2024-05-17', 'Queremos ir a tu pueblo gratis', 1),
(2, 2, 1, '2024-05-16', '2024-05-23', 'Prueba contrato cancelado', 3),
(3, 2, 1, '2024-05-28', '2024-06-09', 'Prueba contrato por aprobar', 4),
(4, 2, 1, '2024-05-21', '2024-05-31', 'Prueba contrato', 5);

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`id`, `nTrabajadores`, `ambito`) VALUES
(2, 1, 1);

--
-- Volcado de datos para la tabla `encargos`
--

INSERT INTO `encargos` (`id`, `idVecino`, `idEmpresa`, `fecha`, `terminos`, `estado`) VALUES
(1, 5, 2, '2024-05-10', 'Prueba encargo por aceptar', 4),
(2, 5, 2, '2024-05-10', 'Prueba encargo aceptado', 1),
(3, 5, 2, '2024-05-10', 'Prueba encargo terminado', 2),
(4, 5, 2, '2024-05-10', 'Prueba encargo a cancelar', 3);

--
-- Volcado de datos para la tabla `notificaciones`
--

INSERT INTO `notificaciones` (`id`, `idReferencia`, `tipo`, `fecha`, `estado`, `idEmisor`, `idReceptor`, `titulo`) VALUES
(1, 1, 3, '2024-05-10 12:22:44', 2, 3, 1, 'Nuevo Anuncio: La página está en funcionamiento'),
(2, 1, 3, '2024-05-10 12:22:44', 2, 3, 2, 'Nuevo Anuncio: La página está en funcionamiento'),
(3, 1, 3, '2024-05-10 12:22:44', 2, 3, 5, 'Nuevo Anuncio: La página está en funcionamiento'),
(4, 1, 1, '2024-05-10 12:24:08', 2, 2, 1, 'Nuevo Contrato Pendiente de Aprobación'),
(5, 1, 1, '2024-05-10 12:24:56', 2, 1, 2, 'Contrato Aprobado'),
(6, 2, 1, '2024-05-10 12:32:48', 2, 2, 1, 'Nuevo Contrato Pendiente de Aprobación'),
(7, 2, 1, '2024-05-10 12:33:15', 1, 2, 1, 'Contrato Cancelado'),
(8, 2, 1, '2024-05-10 12:33:15', 1, 1, 2, 'Contrato Cancelado'),
(9, 3, 1, '2024-05-10 12:34:08', 1, 2, 1, 'Nuevo Contrato Pendiente de Aprobación'),
(10, 4, 1, '2024-05-10 12:34:29', 1, 2, 1, 'Nuevo Contrato Pendiente de Aprobación'),
(11, 4, 1, '2024-05-10 12:35:00', 1, 1, 2, 'Contrato Aprobado'),
(12, 4, 1, '2024-05-10 12:35:51', 1, 2, 1, 'Contrato Modificado'),
(13, 1, 2, '2024-05-10 12:37:18', 1, 5, 2, 'Nuevo Encargo Pendiente'),
(14, 2, 2, '2024-05-10 12:37:35', 1, 5, 2, 'Nuevo Encargo Pendiente'),
(15, 3, 2, '2024-05-10 12:37:51', 1, 5, 2, 'Nuevo Encargo Pendiente'),
(16, 4, 2, '2024-05-10 12:38:22', 1, 5, 2, 'Nuevo Encargo Pendiente'),
(17, 2, 2, '2024-05-10 12:38:50', 1, 2, 5, 'Encargo Aceptado'),
(18, 4, 2, '2024-05-10 12:38:57', 1, 2, 5, 'Encargo Cancelado'),
(19, 4, 2, '2024-05-10 12:38:57', 1, 5, 2, 'Encargo Cancelado'),
(20, 3, 2, '2024-05-10 12:39:06', 1, 2, 5, 'Encargo Aceptado'),
(21, 3, 2, '2024-05-10 12:39:33', 1, 2, 5, 'Encargo Completado'),
(22, 3, 2, '2024-05-10 12:39:33', 1, 5, 2, 'Encargo Completado'),
(23, 5, 1, '2024-05-10 12:42:09', 1, 7, 6, 'Nuevo Contrato Pendiente de Aprobación'),
(24, 6, 1, '2024-05-10 12:42:49', 1, 7, 1, 'Nuevo Contrato Pendiente de Aprobación'),
(25, 5, 2, '2024-05-10 12:45:00', 1, 8, 7, 'Nuevo Encargo Pendiente'),
(26, 6, 2, '2024-05-10 12:48:38', 1, 9, 7, 'Nuevo Encargo Pendiente'),
(27, 7, 2, '2024-05-10 12:52:27', 1, 10, 7, 'Nuevo Encargo Pendiente');

--
-- Volcado de datos para la tabla `pueblos`
--

INSERT INTO `pueblos` (`id`, `cif`, `comunidad`) VALUES
(1, 1, 1);

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombreUsuario`, `password`, `nombre`, `rol`, `nombreImg`) VALUES
(1, 'Pueblo1', '$2y$10$dGY47m/MJGa.Fwc1ELbpKevJfKLhA6ckP710iMmZstWKrHR2m7qty', 'Pueblo1', 3, 'uploads/663df32601d6b.jpg'),
(2, 'Empresa1', '$2y$10$cgqbhljFZc5VBRyefITs3eKk.s1K2lPVMaOhXafG5Dv6BnzPMaIKq', 'Empresa1', 2, 'uploads/663df3a4ac307.jpg'),
(3, 'Admin1', '$2y$10$lVWuGeajYfgbFuFkWLZHXOH2Z.kGDVSf2bQQ3gQzC7mrOvHGcenVq', 'Admin1', 1, 'imagenes/default.png'),
(5, 'Vecino1', '$2y$10$L8.asR19FaItAUuqm8YCv.srHQYw.7WLKorWX.7NHjHrDKdaaKzDC', 'Vecino1', 4, 'uploads/manzana.jpg');

--
-- Volcado de datos para la tabla `vecinos`
--

INSERT INTO `vecinos` (`id`, `idPueblo`) VALUES
(5, 1),
(9, 6);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
