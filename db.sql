CREATE DATABASE vote;
USE vote;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    sudah_vote INT DEFAULT 0
);

INSERT INTO users (username, password, sudah_vote) VALUES 
('a', '1', 0),
('b', '2', 0),
('c', '3', 0);

CREATE TABLE IF NOT EXISTS votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kandidat VARCHAR(10) NOT NULL UNIQUE,
    jumlah INT DEFAULT 0
);

INSERT INTO votes (kandidat, jumlah) VALUES 
('A', 0),
('B', 0),
('C', 0);
