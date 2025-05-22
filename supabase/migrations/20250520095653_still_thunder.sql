/*
  # Initial Database Schema for Canary GPU Marketplace

  1. New Tables
    - `users`
      - Core user information and authentication
      - Includes profile data and ratings
    - `listings`
      - GPU listings with detailed information
      - Linked to users table
    - `listing_images`
      - Stores image paths for listings
      - Supports multiple images per listing
    - `messages`
      - Private messaging system
      - Tracks conversations between users
    - `categories`
      - GPU categories and manufacturers
*/

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    rating DECIMAL(3,2) DEFAULT 0,
    rating_count INT DEFAULT 0,
    email_verified BOOLEAN DEFAULT FALSE,
    reset_token VARCHAR(255) NULL,
    reset_token_expiry TIMESTAMP NULL
);

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    manufacturer VARCHAR(50) NOT NULL
);

-- Listings table
CREATE TABLE IF NOT EXISTS listings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    condition_status ENUM('New', 'Like New', 'Good', 'Fair') NOT NULL,
    category_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('active', 'sold', 'deleted') DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Listing images table
CREATE TABLE IF NOT EXISTS listing_images (
    id INT PRIMARY KEY AUTO_INCREMENT,
    listing_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE
);

-- Messages table
CREATE TABLE IF NOT EXISTS messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sender_id INT NOT NULL,
    recipient_id INT NOT NULL,
    listing_id INT NOT NULL,
    message TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    read_at TIMESTAMP NULL,
    FOREIGN KEY (sender_id) REFERENCES users(id),
    FOREIGN KEY (recipient_id) REFERENCES users(id),
    FOREIGN KEY (listing_id) REFERENCES listings(id)
);

-- Insert default GPU manufacturers
INSERT INTO categories (name, manufacturer) VALUES
    ('GeForce RTX 4090', 'NVIDIA'),
    ('GeForce RTX 4080', 'NVIDIA'),
    ('GeForce RTX 4070 Ti', 'NVIDIA'),
    ('GeForce RTX 4070', 'NVIDIA'),
    ('Radeon RX 7900 XTX', 'AMD'),
    ('Radeon RX 7900 XT', 'AMD'),
    ('Radeon RX 7800 XT', 'AMD'),
    ('Radeon RX 7700 XT', 'AMD');