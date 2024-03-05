/*
  Recuerda que deshabilitar la opción "Enable foreign key checks" para evitar problemas a la hora de importar el script.
*/

TRUNCATE TABLE `Pueblo`;
TRUNCATE TABLE `Empresa`;
TRUNCATE TABLE `Administrador`;

/* ESTO HAY QUE CAMBIARLO PERO NI IDEA DE MOMENTO COMO */
INSERT INTO `Roles` (`id`, `nombre`) VALUES
(1, 'admin'),
(2, 'user');

/* ESTO HAY QUE CAMBIARLO PERO NI IDEA DE MOMENTO COMO */
INSERT INTO `RolesUsuario` (`usuario`, `rol`) VALUES
(1, 1),
(1, 2),
(2, 2);

/*
  user: userpass
  admin: adminpass
*/
INSERT INTO `Usuarios` (`id`, `nombreUsuario`, `nombre`, `password`) VALUES
(1, 'admin', 'Administrador', 'adminpass'),
(2, 'pueblo', 'Pueblo', 'pueblopass');
(2, 'empresa', 'Empresa', 'empresapass');

/*
SET @INICIO := NOW();
INSERT INTO `Mensajes` (`id`, `autor`, `mensaje`, `fechaHora`, `idMensajePadre`) VALUES
(1, 1, 'Bienvenido al foro', @INICIO, NULL),
(2, 2, 'Muchas gracias', ADDTIME(@INICIO, '0:15:0'), 1),
(3, 2, 'Otro mensaje', ADDTIME(@INICIO, '25:15:0'), NULL);
*/