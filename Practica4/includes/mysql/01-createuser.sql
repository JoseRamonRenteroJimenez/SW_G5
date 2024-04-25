CREATE USER 'ejercicio3'@'%' IDENTIFIED BY 'practica3';
GRANT ALL PRIVILEGES ON `practica3`.* TO 'practica3'@'%';

CREATE USER 'ejercicio3'@'localhost' IDENTIFIED BY 'practica3';
GRANT ALL PRIVILEGES ON `practica3`.* TO 'practica3'@'localhost';