
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


CREATE TABLE prescription_preview_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(255),
    image_path VARCHAR(255),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE quotations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prescription_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (prescription_id) REFERENCES prescriptions(id)
);

CREATE TABLE quotation_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quotation_id INT NOT NULL,
    drug_name VARCHAR(100),
    quantity INT,
    unit_price DECIMAL(10,2),
    FOREIGN KEY (quotation_id) REFERENCES quotations(id)
);

CREATE TABLE quotations_preview (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(255),
    drug_name VARCHAR(100),
    quantity INT,
    unit_price DECIMAL(10,2),
    amount DECIMAL(10,2)
);
