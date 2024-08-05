CREATE DATABASE IF NOT EXISTS ticket_shop;

USE ticket_shop;

CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE ticket_types
(
    id     INT AUTO_INCREMENT PRIMARY KEY,
    `type` VARCHAR(50)    NOT NULL UNIQUE,
    price  DECIMAL(10, 2) NOT NULL
);

CREATE TABLE IF NOT EXISTS tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    ticket_type_id INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES customers (id),
    FOREIGN KEY (ticket_type_id) REFERENCES ticket_types (id)
);

INSERT INTO ticket_types (`type`, price)
VALUES ('Regular', 2),
       ('Vip', 3);
