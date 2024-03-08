-- Truncar la tabla `admin`
TRUNCATE TABLE `admin`;

-- Truncar la tabla `chat`
TRUNCATE TABLE `chat`;

-- Truncar la tabla `contrato`
TRUNCATE TABLE `contrato`;

-- Truncar la tabla `cooperativa`
TRUNCATE TABLE `cooperativa`;

-- Truncar la tabla `empresa`
TRUNCATE TABLE `empresa`;

-- Truncar la tabla `noticia`
TRUNCATE TABLE `noticia`;

-- Truncar la tabla `pueblo`
TRUNCATE TABLE `pueblo`;


-- Insertar datos en la tabla `admin`
INSERT INTO `admin` (`permisos`, `password`) VALUES
(1, 'contraseña_admin_1'),
(2, 'contraseña_admin_2'),
(3, 'contraseña_admin_3');

-- Insertar datos en la tabla `chat`
INSERT INTO `chat` (`mensajes`, `integranteEmpresa`, `integrantePueblo`) VALUES
('Mensaje1', 1, 2),
('Mensaje2', 3, 4),
('Mensaje3', 5, 6);

-- Insertar datos en la tabla `contrato`
INSERT INTO `contrato` (`tiempo`, `terminos`, `pueblo`, `empresa`, `admin`) VALUES
('2024-03-08', '{"term1": "value1"}', 1, 2, 1),
('2024-03-09', '{"term2": "value2"}', 3, 4, 2),
('2024-03-10', '{"term3": "value3"}', 5, 6, 3);

-- Insertar datos en la tabla `cooperativa`
INSERT INTO `cooperativa` (`integrantes`, `contratos`) VALUES
(1, 2),
(3, 4),
(5, 6);

-- Insertar datos en la tabla `empresa`
INSERT INTO `empresa` (`nombre`, `nTrabajadores`, `ambito`, `contratos`, `cooperativas`, `password`) VALUES
('Empresa1', 100, '{"ambito1": "value1"}', 1, 2, 'contraseña_empresa_1'),
('Empresa2', 200, '{"ambito2": "value2"}', 3, 4, 'contraseña_empresa_2'),
('Empresa3', 300, '{"ambito3": "value3"}', 5, 6, 'contraseña_empresa_3');

-- Insertar datos en la tabla `noticia`
INSERT INTO `noticia` (`descripcion`, `autor`) VALUES
('Noticia1', 1),
('Noticia2', 2),
('Noticia3', 3);

-- Insertar datos en la tabla `pueblo`
INSERT INTO `pueblo` (`c.a.`, `nombre`, `servicios`, `password`) VALUES
('Andalucía', 'Pueblo1', '{"servicio1": "value1"}', 'contraseña_pueblo_1'),
('Aragón', 'Pueblo2', '{"servicio2": "value2"}', 'contraseña_pueblo_2'),
('Principado de Asturias', 'Pueblo3', '{"servicio3": "value3"}', 'contraseña_pueblo_3');
