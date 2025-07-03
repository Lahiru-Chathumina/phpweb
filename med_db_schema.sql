
CREATE DATABASE IF NOT EXISTS med_db;
USE med_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    address TEXT,
    contact_no VARCHAR(20),
    dob DATE,
    role ENUM('user', 'pharmacy') DEFAULT 'user'
);

CREATE TABLE prescriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    note TEXT,
    delivery_address TEXT,
    delivery_slot VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE prescription_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prescription_id INT,
    image_path VARCHAR(255),
    FOREIGN KEY (prescription_id) REFERENCES prescriptions(id)
);

CREATE TABLE quotations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prescription_id INT,
    total_amount DECIMAL(10,2),
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (prescription_id) REFERENCES prescriptions(id)
);

CREATE TABLE quotation_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quotation_id INT,
    drug_name VARCHAR(100),
    quantity INT,
    unit_price DECIMAL(10,2),
    FOREIGN KEY (quotation_id) REFERENCES quotations(id)
);
