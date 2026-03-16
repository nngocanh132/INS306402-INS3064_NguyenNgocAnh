-- =========================================
-- B2: Employee Directory - ENUM & Salary Precision
-- =========================================

CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,

    full_name VARCHAR(150) NOT NULL,

    email VARCHAR(150) UNIQUE,

    department ENUM('HR','Engineering','Marketing','Sales','Finance') NOT NULL,

    salary DECIMAL(15,2) NOT NULL,

    hire_date DATE NOT NULL,

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);