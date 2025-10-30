CREATE DATABASE IF NOT EXISTS statmod_db DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE statmod_db;

CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  short_desc VARCHAR(255),
  description TEXT,
  price DECIMAL(10,2) DEFAULT 0.00,
  category VARCHAR(100),
  image VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert demo admin (username: admin, password: admin123) - you can change after import
INSERT INTO admins (username, password_hash) VALUES ('admin', '$2y$10$wH6J1J6kJ8ZbQwZf7sQYkO7q7tVb2kVxYJ0FvQxW1Gq9uKZ1l1x2'); -- admin123
INSERT INTO products (title, short_desc, description, price, category) VALUES
  ('Advanced Regression Pack', 'Tools & scripts for modeling', 'A starter pack for statistical modeling.', 499.00, 'Data'),
  ('Time Series Essentials', 'Kits & tutorials', 'Practical time series templates and examples.', 699.00, 'Time Series');
