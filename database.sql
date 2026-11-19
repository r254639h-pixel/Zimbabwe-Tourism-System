CREATE DATABASE IF NOT EXISTS tourism_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tourism_db;

CREATE TABLE IF NOT EXISTS destinations (
  destination_id INT AUTO_INCREMENT PRIMARY KEY,
  destination_name VARCHAR(100) NOT NULL,
  location VARCHAR(100) NOT NULL,
  description TEXT NOT NULL,
  image VARCHAR(255) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS hotels (
  hotel_id INT AUTO_INCREMENT PRIMARY KEY,
  hotel_name VARCHAR(100) NOT NULL,
  destination_id INT NOT NULL,
  location VARCHAR(100) NOT NULL,
  description TEXT DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_hotels_destination FOREIGN KEY (destination_id)
    REFERENCES destinations(destination_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS administrators (
  admin_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

INSERT INTO destinations (destination_name, location, description) VALUES
('Victoria Falls', 'Matabeleland North Province', 'One of the world’s most famous waterfalls and a major tourist attraction in Zimbabwe.'),
('Great Zimbabwe', 'Masvingo Province', 'An important historical site known for its impressive ancient stone ruins.'),
('Hwange National Park', 'Matabeleland North Province', 'A large national park known for its wildlife and elephant population.'),
('Matobo National Park', 'Matabeleland South Province', 'A beautiful area known for granite hills, rock formations and cultural heritage.');
