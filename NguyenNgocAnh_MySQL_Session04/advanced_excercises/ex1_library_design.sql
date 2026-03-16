-- =========================================
-- B1: Library System - Design Under Uncertainty
-- =========================================

-- Create tables for library system

-- =========================================
-- Table: books
-- =========================================
CREATE DATABASE library_db;
USE library_db;
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(150) NOT NULL,
    isbn VARCHAR(20) UNIQUE NOT NULL,
    published_year INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- Table: members
-- =========================================

CREATE TABLE members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE,
    phone VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- Table: borrow_records
-- =========================================

CREATE TABLE borrow_records (
    id INT AUTO_INCREMENT PRIMARY KEY,

    book_id INT NOT NULL,
    member_id INT NOT NULL,

    borrow_date DATE NOT NULL,
    due_date DATE NOT NULL,
    return_date DATE,

    CONSTRAINT fk_borrow_book
    FOREIGN KEY (book_id)
    REFERENCES books(id),

    CONSTRAINT fk_borrow_member
    FOREIGN KEY (member_id)
    REFERENCES members(id)
);