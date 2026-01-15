CREATE DATABASE IF NOT EXISTS reflex_plans CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE reflex_plans;

CREATE TABLE plans (
  id VARCHAR(32) PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  img VARCHAR(255) NOT NULL,
  short_desc VARCHAR(255) NOT NULL,
  full_desc TEXT NOT NULL,
  old_price DECIMAL(10,2) NOT NULL DEFAULT 0,
  new_price DECIMAL(10,2) NOT NULL DEFAULT 0,
  bedrooms INT NOT NULL DEFAULT 0,
  bathrooms INT NOT NULL DEFAULT 0,
  garage INT NOT NULL DEFAULT 0,
  sqm DECIMAL(10,2) NOT NULL DEFAULT 0,
  stories INT NOT NULL DEFAULT 1,
  style VARCHAR(80) NOT NULL,
  dimensions VARCHAR(80) NOT NULL,
  floor_plan VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE plan_features (
  id INT AUTO_INCREMENT PRIMARY KEY,
  plan_id VARCHAR(32) NOT NULL,
  feature VARCHAR(255) NOT NULL,
  FOREIGN KEY (plan_id) REFERENCES plans(id) ON DELETE CASCADE
);

CREATE TABLE plan_gallery (
  id INT AUTO_INCREMENT PRIMARY KEY,
  plan_id VARCHAR(32) NOT NULL,
  image VARCHAR(255) NOT NULL,
  FOREIGN KEY (plan_id) REFERENCES plans(id) ON DELETE CASCADE
);

CREATE INDEX idx_plans_style ON plans(style);
CREATE INDEX idx_plans_bedrooms ON plans(bedrooms);
CREATE INDEX idx_plans_price ON plans(new_price);
