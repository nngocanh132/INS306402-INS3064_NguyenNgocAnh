-- =========================================
-- A3: Blog Platform - Rebuild a Real Schema
-- =========================================

-- Create database
CREATE DATABASE blog_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE blog_db;

-- =========================================
-- Table: users
-- =========================================

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','editor','user') DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- Table: categories
-- =========================================

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- Table: posts
-- =========================================

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content LONGTEXT,
    author_id INT,
    category_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_posts_user
    FOREIGN KEY (author_id)
    REFERENCES users(id),

    CONSTRAINT fk_posts_category
    FOREIGN KEY (category_id)
    REFERENCES categories(id)
);

-- =========================================
-- Table: comments
-- =========================================

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    parent_id INT,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_comments_post
    FOREIGN KEY (post_id)
    REFERENCES posts(id)
    ON DELETE CASCADE,

    CONSTRAINT fk_comments_parent
    FOREIGN KEY (parent_id)
    REFERENCES comments(id)
);