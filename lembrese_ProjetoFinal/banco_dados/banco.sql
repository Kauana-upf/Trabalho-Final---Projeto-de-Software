CREATE DATABASE lembrese;
USE lembrese;

-- Usuários
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL
);

-- Lembretes
CREATE TABLE reminders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  type ENUM('WATER','MEDICINE','ACTIVITY') NOT NULL,
  title VARCHAR(200) NOT NULL,
  description TEXT,
  date DATE NULL,
  time TIME NOT NULL,
  repeat_daily TINYINT(1) DEFAULT 0,
  status ENUM('PENDING','DONE') DEFAULT 'PENDING',
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
