-- Creación de la tabla Empresa
CREATE TABLE Empresa (
    Nombre VARCHAR(255) NOT NULL,
    CIF VARCHAR(50) NOT NULL UNIQUE,
    Ambito VARCHAR(50) NOT NULL,
    NumeroEmpleados INT NOT NULL,
    ListaEmpleados TEXT, -- Considerar normalización para una gestión más eficiente
    Telefono VARCHAR(50) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    Contraseña VARCHAR(255) NOT NULL,
    Usuario VARCHAR(50) NOT NULL UNIQUE,
    Permisos VARCHAR(255) NOT NULL,
    PRIMARY KEY (CIF)
);

-- Creación de la tabla Pueblo
CREATE TABLE Pueblo (
    Nombre VARCHAR(255) NOT NULL,
    ID INT AUTO_INCREMENT NOT NULL,
    Poblacion INT NOT NULL,
    ComunidadAutonoma VARCHAR(100) NOT NULL,
    Ciudad VARCHAR(100) NOT NULL,
    Anuncios TEXT, -- Considerar uso de otra tabla para gestionar los anuncios
    Recursos TEXT, -- Considerar uso de otra tabla para gestionar los recursos
    Contraseña VARCHAR(255) NOT NULL,
    Usuario VARCHAR(50) NOT NULL UNIQUE,
    Permisos VARCHAR(255) NOT NULL,
    PRIMARY KEY (ID)
);

-- Creación de la tabla Administrador
CREATE TABLE Administrador (
    ID INT AUTO_INCREMENT NOT NULL,
    Contraseña VARCHAR(255) NOT NULL,
    Usuario VARCHAR(50) NOT NULL UNIQUE,
    Permisos VARCHAR(255) NOT NULL,
    PRIMARY KEY (ID)
);
