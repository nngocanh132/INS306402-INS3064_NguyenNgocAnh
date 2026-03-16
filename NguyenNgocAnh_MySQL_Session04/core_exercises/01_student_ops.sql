-- =========================================
-- A1: Student Ops - Build the Student Database
-- =========================================

-- Create database with utf8mb4 collation
CREATE DATABASE student_management_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

-- Use the database
USE student_management_db;

-- =========================================
-- Table: classes
-- =========================================

CREATE TABLE classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_name VARCHAR(100) NOT NULL,
    department VARCHAR(100)
);

-- =========================================
-- Table: students
-- =========================================

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_code VARCHAR(50) UNIQUE,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    age INT,
    class_id INT,

    CONSTRAINT fk_students_class
    FOREIGN KEY (class_id)
    REFERENCES classes(id)
);