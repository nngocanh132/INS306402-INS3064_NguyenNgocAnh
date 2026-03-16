-- =========================================
-- A2: Shop Inventory - Constraints as Business Rules
-- =========================================

USE student_management_db;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,

    product_name VARCHAR(150) NOT NULL,

    sku VARCHAR(50) UNIQUE NOT NULL,

    price DECIMAL(10,2) NOT NULL CHECK (price > 0),

    stock_quantity INT DEFAULT 0,

    is_active BOOLEAN DEFAULT TRUE,

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);