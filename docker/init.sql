CREATE DATABASE IF NOT EXISTS internal_db;
USE internal_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2)
);

-- Admin password: admin123 (SHA1)
INSERT INTO users (username, password, is_admin) 
VALUES ('admin', 'f865b53623b121fd34ee5426c792e5c33af8c227', 1);

-- Sample products
INSERT INTO products (name, description, price) VALUES
('Product 1', 'Description for product 1', 19.99),
('Product 2', 'Description for product 2', 29.99),
('Secret Product', 'Internal use only', 999.99);

-- Grant permissions to internal_user
GRANT SELECT ON internal_db.* TO 'internal_user'@'%';
FLUSH PRIVILEGES;