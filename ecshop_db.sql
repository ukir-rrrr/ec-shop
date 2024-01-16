CREATE DATABASE ecshop_db default character SET utf8;

use ecshop_db;

grant all privileges on ecshop_db. *to ecshop_user@'localhost' identified by 'ecshop_pass' with grant option;


CREATE TABLE Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Address TEXT,
    Phone VARCHAR(20),
    Last_name VARCHAR(50),
    First_name VARCHAR(50),
    Last_name_kana VARCHAR(50),
    First_name_kana VARCHAR(50),
    Zipcode VARCHAR(7),
    status VARCHAR(10) NOT NULL DEFAULT 'active'
);

CREATE TABLE staff (
    code INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20) NOT NULL,
    Password VARCHAR(255) NOT NULL
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    image_path VARCHAR(255)
);

-- 問い合わせ
CREATE TABLE inquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- 売上
CREATE TABLE Sales (
    OrderID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT,
    OrderDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    TotalAmount INT,
    Last_name VARCHAR(50),
    First_name VARCHAR(50),
    Zipcode VARCHAR(7),
    Address TEXT,
    PaymentMethod VARCHAR(20),
    ProductID INT,
    Quantity INT,
    FOREIGN KEY (UserID) REFERENCES Users(UserID),
    FOREIGN KEY (ProductID) REFERENCES products(id)
);
