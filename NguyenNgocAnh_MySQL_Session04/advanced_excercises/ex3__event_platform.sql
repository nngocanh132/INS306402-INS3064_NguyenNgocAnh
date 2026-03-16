-- =========================================
-- B3: Event Platform - JSON for the Unknown
-- =========================================

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,

    event_name VARCHAR(200) NOT NULL,

    start_time DATETIME NOT NULL,

    end_time DATETIME NOT NULL,

    location VARCHAR(255),

    event_details JSON,

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);