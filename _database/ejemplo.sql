SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- Base de datos (ejemplo): 
DROP DATABASE IF EXISTS ejemplo;
CREATE DATABASE ejemplo character set utf8 collate utf8_general_ci;

USE ejemplo;

-- users
CREATE TABLE IF NOT EXISTS users (
  id int(10) NOT NULL AUTO_INCREMENT,
  email varchar(50) NOT NULL,
  name varchar(20) NOT NULL,
  lastname varchar(50) NULL, /* Permite nulos */
  username varchar(20) NOT NULL,
  password varchar(35) NOT NULL,
  age int(3) NULL, /* Permite nulos */
  role ENUM('admin', 'registered') NOT NULL DEFAULT 'registered',
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);


-- Volcado de datos para la tabla 'users'
INSERT INTO users (email, name, lastname, username, password, age, role) VALUES
('admin@debut.com', 'administrator', NULL, 'admin', MD5('secret'), NULL, 1),
('example1@email.com', 'Daniel', 'Martínez', 'DanMnez', MD5('1234'), 23, default),
('example2@email.com', 'Ramón', 'Alcolea', 'Ramonico', MD5('546364'), 45, default),
('example3@email.com', 'José', 'Patiño Alvarez', 'Joselín', MD5('332443ds'), 19, default),
('example4@email.com', 'Marta', 'Robles', 'Mart-45', MD5('838ddsw'), 18, default),
('example5@email.com', 'Manuel', 'Serrano', 'Manu', MD5('kfoso49'), 59, default),
('example6@email.com', 'Antonio José', 'Lopez', 'Ant-js', MD5('88293'), 32, default),
('example7@email.com', 'Pablo', 'Pedrosa', 'Pablo-31', MD5('lolo534'), 45, default);
