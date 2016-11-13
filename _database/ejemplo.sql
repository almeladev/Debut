DROP DATABASE IF EXISTS debut;
CREATE DATABASE debut character set utf8 collate utf8_general_ci;

USE debut;


-- users
CREATE TABLE IF NOT EXISTS users (
  id int NOT NULL AUTO_INCREMENT,
  email varchar(255) NOT NULL,
  name varchar(255) NOT NULL,
  password varchar(255) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);


-- posts
CREATE TABLE IF NOT EXISTS posts (
  id int NOT NULL AUTO_INCREMENT,
  title varchar(255) NOT NULL,
  content text NOT NULL,
  user_id int NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY (title),
  FOREIGN KEY (user_id) REFERENCES users(id)
);


-- Volcado de datos
INSERT INTO users (email, name, password) VALUES
('admin@debut.app', 'Admin', MD5('secret')),
('juan@debut.app', 'Juan', MD5('secret')),
('pedro@debut.app', 'Pedro', MD5('secret')),
('tomas@debut.app', 'Tomas', MD5('secret')),
('laura@debut.app', 'Laura', MD5('secret')),
('agustin@debut.app', 'Agustín', MD5('secret')),
('sara@debut.app', 'Sara', MD5('secret')),
('tamara@debut.app', 'Tamara', MD5('secret')),
('luis@debut.app', 'Luis', MD5('secret'));

INSERT INTO posts (title, content, user_id) VALUES
('Hello world!', 'Este es el primer post de la página!', 1);


