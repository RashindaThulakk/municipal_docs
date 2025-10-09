-- municipal_docs.sql
CREATE DATABASE IF NOT EXISTS municipal_docs CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE municipal_docs;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('Admin','Clerk','User') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE building_applications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ba_no VARCHAR(50) NOT NULL,
  year YEAR NOT NULL,
  rack_number VARCHAR(10) NOT NULL,
  applicant_name VARCHAR(255) NOT NULL,
  applicant_id VARCHAR(100),
  assessment_no VARCHAR(100),
  street_name VARCHAR(255),
  assessment_ward TINYINT UNSIGNED,
  officer_received VARCHAR(255),
  date_taken DATE,
  attachment LONGBLOB,
  attachment_type VARCHAR(100),
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_ba_no (ba_no),
  INDEX idx_applicant_id (applicant_id),
  INDEX idx_year (year)
);
