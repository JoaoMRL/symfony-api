CREATE DATABASE api_rest
    DEFAULT CHARACTER SET = 'utf8mb4';
use api_rest;
DROP TABLE IF EXISTS movie;
CREATE TABLE movie (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    resume TEXT NOT NULL,
    released DATE NOT NULL,
    duree int
);

INSERT INTO movie (title,resume,released,duree) VALUES ("Jojo Le commancement","Nous découvront un gentlement du nom de Jonathan Joestar","2022/08/16",120);
SELECT * FROM movie;