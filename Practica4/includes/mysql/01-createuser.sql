CREATE USER 'practica4'@'%' IDENTIFIED BY 'practica4';
GRANT ALL PRIVILEGES ON `practica4`.* TO 'practica4'@'%';

CREATE USER 'practica4'@'localhost' IDENTIFIED BY 'practica4';
GRANT ALL PRIVILEGES ON `practica4`.* TO 'practica4'@'localhost';