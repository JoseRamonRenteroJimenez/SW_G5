/*
  Recuerda que deshabilitar la opción "Enable foreign key checks" para evitar problemas a la hora de importar el script.
*/
TRUNCATE TABLE `RolesUsuario`;
TRUNCATE TABLE `Roles`;
TRUNCATE TABLE `Usuarios`;

-- Añadir roles
INSERT INTO roles (`id`, `nombre`) VALUES
(1, 'Admin'), -- ID 1
(2, 'Empresa'), -- ID 2
(3, 'Pueblo'); -- ID 3


-- Agregar entradas en la tabla comunidades
INSERT INTO comunidades (nombre) VALUES
('Andalucía'),
('Aragón'),
('Islas Baleares'),
('Canarias'),
('Cantabria'),
('Castilla-La Mancha'),
('Castilla y León'),
('Cataluña'),
('Comunidad de Madrid'),
('Comunidad Foral de Navarra'),
('Comunidad Valenciana'),
('Extremadura'),
('Galicia'),
('País Vasco'),
('Principado de Asturias'),
('Región de Murcia'),
('La Rioja');

-- Insertar datos en la tabla ambitos
INSERT INTO ambitos (id, nombre) VALUES
(1, 'Tecnología'),
(2, 'Salud'),
(3, 'Turismo'),
(4, 'Educación');

-- Insertar datos en la tabla empresas
INSERT INTO empresas (id, nTrabajadores, ambito) VALUES
(2, 50, 1), -- Empresa de tecnología con 50 trabajadores
(4, 30, 2), -- Empresa de salud con 30 trabajadores
(6, 20, 3); -- Empresa de turismo con 20 trabajadores

-- Insertar datos en la tabla pueblos
INSERT INTO pueblos (id, cif, comunidad) VALUES
(3, 123456, 1), -- Pueblo en Andalucía
(5, 234567, 7), -- Pueblo en Castilla y León
(7, 345678, 9); -- Pueblo en Comunidad de Madrid

-- Insertar datos en la tabla contratos
INSERT INTO contratos (id, idEmpresa, idPueblo, duracion, terminos) VALUES
(1, 1, 1, 30, 'Condiciones de contrato 1'),
(2, 2, 2, 60, 'Condiciones de contrato 2'),
(3, 3, 3, 90, 'Condiciones de contrato 3');

-- Insertar datos en la tabla servicios
INSERT INTO servicios (id, idPueblo, idAmbito, cantidad) VALUES
(1, 1, 1, 5), -- Servicios de tecnología en pueblo 1
(2, 2, 2, 3), -- Servicios de salud en pueblo 2
(3, 3, 3, 2); -- Servicios de turismo en pueblo 3

-- Insertar datos en la tabla usuarios
INSERT INTO usuarios (id, nombreUsuario, password, nombre, rol) VALUES
(2, 'empresa1', 'empresa123', 'Empresa 1', 2), -- Usuario de empresa
(3, 'pueblo1', 'pueblo123', 'Pueblo 1', 3), -- Usuario de pueblo
(4, 'empresa2', 'empresa456', 'Empresa 2', 2), -- Usuario de empresa
(5, 'pueblo2', 'pueblo456', 'Pueblo 2', 3), -- Usuario de pueblo
(6, 'empresa3', 'empresa789', 'Empresa 3', 2), -- Usuario de empresa
(7, 'pueblo3', 'pueblo789', 'Pueblo 3', 3); -- Usuario de pueblo


/*
  user: userpass
  admin: adminpass

INSERT INTO `Usuarios` (`id`, `nombreUsuario`, `nombre`, `password`) VALUES
(1, 'admin', 'Administrador', '$2y$10$O3c1kBFa2yDK5F47IUqusOJmIANjHP6EiPyke5dD18ldJEow.e0eS'),
(2, 'user', 'Usuario', '$2y$10$uM6NtF.f6e.1Ffu2rMWYV.j.X8lhWq9l8PwJcs9/ioVKTGqink6DG');
*/