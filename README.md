# SW_G5
Pueblo Innova
Descripción General

Pueblo Innova es una aplicación web desarrollada para conectar áreas rurales con empresas, facilitando la revitalización de zonas despobladas en España. La plataforma tiene como objetivo facilitar la integración de pueblos preparados para acoger nuevas empresas y residentes, ofreciendo a las empresas la oportunidad de trasladarse de zonas urbanas congestionadas a entornos rurales más tranquilos y sostenibles. Este proyecto fue desarrollado como parte de la asignatura de Sistemas Web de la Facultad de Informática de la Universidad Complutense de Madrid por el Grupo 05, compuesto por Javier Daza Justo, José Ramón Rentero Jiménez y Adrián Dumitru Chitic.
Tabla de Contenidos

    Introducción
        Idea Original
        Idea Definitiva Implementada
    Tecnologías Utilizadas
    Estructura del Código
        Includes (Clases)
        Includes (Formularios)
        Includes (Vistas/Común)
    Configuración de la Aplicación
    Información sobre la BBDD
    Funcionalidades Más Importantes
    Datos de Prueba para Utilizar
    Novedades Introducidas para Esta Versión
    Guía de Usuario
    Aportaciones Individuales
    Instrucciones de Despliegue

Introducción
Idea Original

Pueblo Innova nació con la idea de facilitar la integración de pueblos y empresas, abordando el problema de la "España Despoblada". Proporciona una plataforma donde los pueblos preparados para acoger nuevas empresas y residentes pueden conectarse con empresas interesadas en trasladarse de áreas urbanas a entornos rurales. El objetivo es fomentar un estilo de vida más tranquilo y sostenible donde la comunidad y la innovación se unan.
Idea Definitiva Implementada

El concepto original se adaptó debido a cambios en el alcance del proyecto y la reestructuración del equipo. Se eliminaron características clave como el chat en vivo y los servicios de mudanza, pero se añadieron nuevas funcionalidades para mejorar el proyecto. La implementación final incluye cuatro roles de usuario distintos: Pueblo, Empresa, Administrador y Vecino.
Tecnologías Utilizadas

    HTML: Estructura inicial y elementos básicos.
    CSS: Estilización y mejoras en el diseño del front-end.
    PHP: Principal lenguaje de backend, constituyendo el 90% del código.
    JavaScript & AJAX: Interacciones en tiempo real y validación de formularios, mejorando la experiencia del usuario con notificaciones instantáneas y cargas de imágenes.

Estructura del Código

El proyecto sigue un patrón estructurado para una mejor legibilidad y mantenimiento. Las principales carpetas y su contenido incluyen:
Includes (Clases)

    Usuario.php: Gestiona perfiles de usuario y funcionalidades generales.
    Administrador.php: Maneja acciones específicas de administrador y notificaciones.
    Empresa.php: Gestiona datos y acciones específicas de empresas.
    Pueblo.php: Maneja datos y acciones específicas de pueblos.
    Vecino.php: Nueva clase añadida para acciones y datos específicos de vecinos.
    Contrato.php: Gestiona contratos entre pueblos y empresas.
    Encargo.php: Maneja solicitudes de vecinos a empresas.
    Ámbito.php: Gestiona el ámbito de las empresas.
    Anuncio.php: Gestiona anuncios, incluyendo creación, modificación y eliminación.
    Comunidad.php: Gestiona comunidades autónomas.
    Notificación.php: Maneja notificaciones entre usuarios.

Includes (Formularios)

Los formularios son esenciales para un flujo de datos consistente y seguro. Las principales clases de formularios incluyen:

    Formulario.php: Clase base para el manejo de errores y procesamiento de formularios.
    Formularios para Anuncios, Contratos y Encargos: Manejan acciones específicas relacionadas con estos elementos.
    FormularioPerfil.php: Gestiona acciones del perfil de usuario.
    FormularioLogin.php: Maneja el inicio de sesión de usuarios.
    FormularioRegistroRol.php: Maneja el registro de usuarios basado en roles.
    FormularioSoporte.php: Gestiona solicitudes de soporte a los administradores.

Includes (Vistas/Común)

Estos archivos definen la estructura general y los elementos comunes de las páginas web:

    Admin.php: Vista para el área de administración.
    Faq.php: Página de Preguntas Frecuentes.
    Index.php: Página de inicio.
    Login.php: Página de inicio de sesión.
    Logout.php: Funcionalidad de cierre de sesión.
    Soporte.php: Página de soporte.
    Vistas para Anuncios, Contratos, Encargos, Notificaciones y Perfiles: Manejan vistas detalladas y acciones relacionadas con estos elementos.

Configuración de la Aplicación

Archivos clave para la configuración de la aplicación incluyen:

    Aplicación.php: Gestiona las conexiones a la base de datos y la inicialización de la aplicación.
    Config.php: Define constantes de la base de datos y configuraciones de la aplicación, incluyendo parámetros de conexión y ajustes de la aplicación.
    Plantilla.php: Define la plantilla de la página, incluyendo la carga de CSS y la estructura de la página.

Información sobre la BBDD

El proyecto utiliza una base de datos MySQL con varias tablas para gestionar diferentes entidades. Las modificaciones recientes incluyen nuevas tablas para Encargos y Notificaciones, y la eliminación de tablas obsoletas Servicios y Roles.
Funcionalidades Más Importantes

    Anuncios: Los usuarios pueden crear, modificar y eliminar anuncios. Los anuncios de los administradores generan notificaciones.
    Contratos: Gestiona acuerdos entre pueblos y empresas.
    Encargos: Los vecinos pueden hacer solicitudes a empresas.
    Gestión de Perfiles: Los usuarios pueden editar y eliminar sus perfiles, actualizar fotos de perfil y recibir notificaciones en tiempo real.
    Notificaciones: Mantiene a los usuarios informados sobre acciones y actualizaciones importantes.

Datos de Prueba para Utilizar

Credenciales de inicio de sesión para diferentes roles:

    Pueblo: Pueblo1 / Pueblo1
    Empresa: Empresa1 / Empresa1
    Administrador: Admin1 / Admin1
    Vecino: Vecino1 / Vecino1

Datos pre-cargados para probar incluyen varios anuncios, contratos y encargos.
Novedades Introducidas para Esta Versión

    Mejoras en el CSS y el diseño de la página de inicio.
    Funcionalidad de carga de imágenes.
    Nuevo rol "Vecino".
    Los vecinos pueden formalizar encargos con las empresas en su pueblo.
    El tablón de anuncios ahora permite la carga de imágenes.
    Mejoras en los campos de contratos.
    Corrección del error en el buscador de pueblos por comunidad autónoma.
    Listados más legibles.
    Registro mejorado con un selector de roles.
    Implementación de notificaciones en tiempo real.
    Mejoras en la seguridad y validación de datos.
    Modificaciones en varias tablas de la base de datos.
    Uso de JSON y AJAX.
    Reestructuración de formularios para reducir código repetido.
    Posibilidad de modificar y observar contratos, encargos y anuncios, en masa o específicamente.

Guía de Usuario
Guía Visual

    Página de inicio: Vista general de la plataforma.
    Inicio de sesión: Acceso para usuarios registrados.
    Registro: Registro de usuarios con campos específicos según el rol.
    Publicación de anuncio: Creación de nuevos anuncios.
    Creación de contrato: Establecimiento de contratos entre pueblos y empresas.
    Listado de notificaciones: Visualización de notificaciones.
    Gestión de perfil: Edición y gestión de perfiles de usuario.
    Creación de encargo: Los vecinos pueden realizar encargos a las empresas.

Aportaciones Individuales

    José Ramón Rentero Jiménez: Sistema de notificaciones, patrones de formularios, implementación de JavaScript y AJAX para notificaciones, desarrollo del rol "Vecino", despliegue del proyecto.
    Adrián Dumitru Chitic: Modificaciones en el CSS, funcionalidad de carga de imágenes, manejo de errores y validación, contribuciones al sistema de notificaciones.
    Javier Daza Justo: Detección y corrección de errores, cambios en el registro, desarrollo del rol "Vecino", patrones de formularios y vistas, documentación del README, eliminación de archivos obsoletos.

Instrucciones de Despliegue

El proyecto está desplegado en un VPS accesible en: Pueblo Innova VPS.
Acceso a la Base de Datos

    PHPMyAdmin: PHPMyAdmin
        Usuario: root
        Servidor: vm006
        Contraseña: 4YRkH6YDcy5QfdPNAuqf

Configuración

Actualiza el archivo config.php con los parámetros de la base de datos y configuraciones de la aplicación adecuados para asegurar el correcto funcionamiento.
