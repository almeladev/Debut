DROP DATABASE IF EXISTS debut;
CREATE DATABASE debut;


-- users
CREATE TABLE IF NOT EXISTS debut.users (
  id SERIAL,
  email varchar(255) NOT NULL UNIQUE,
  name varchar(255) NOT NULL,
  password varchar(255) NOT NULL,
  PRIMARY KEY (id)
);


-- posts
CREATE TABLE IF NOT EXISTS debut.posts (
  id SERIAL,
  title varchar(255) NOT NULL UNIQUE,
  content text NOT NULL,
  user_id int DEFAULT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
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
('Hello world!', 'Este es el primer post de la página!', 1),
('Otro post de ejemplo', 'Este es otro post de ejemplo creado por otro usuario', 4);

