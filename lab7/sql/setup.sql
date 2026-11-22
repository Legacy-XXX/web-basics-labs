-- Створення бази даних і користувача для лабораторних робіт №7–8
CREATE DATABASE IF NOT EXISTS klats_lab CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'klats'@'localhost' IDENTIFIED BY 'klats_pass_2026';
GRANT ALL PRIVILEGES ON klats_lab.* TO 'klats'@'localhost';
FLUSH PRIVILEGES;
