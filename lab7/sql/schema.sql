-- Таблиця замовлень магазину «Клац»
CREATE TABLE IF NOT EXISTS orders (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100)  NOT NULL,
    product       VARCHAR(150)  NOT NULL,
    category      VARCHAR(30)   NOT NULL,
    quantity      INT UNSIGNED  NOT NULL,
    price         DECIMAL(10,2) NOT NULL,
    email         VARCHAR(150)  NOT NULL,
    phone         VARCHAR(20)   NULL,
    comment       TEXT          NULL,
    created_at    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
