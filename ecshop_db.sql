CREATE DATABASE ecshop_db default character SET utf8;

use ecshop_db;

grant all privileges on ecshop_db. *to ecshop_user@'localhost' identified by 'ecshop_pass' with grant option;


CREATE TABLE Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Address TEXT,
    Phone VARCHAR(20),
    Last_name VARCHAR(50),
    First_name VARCHAR(50),
    Last_name_kana VARCHAR(50),
    First_name_kana VARCHAR(50),
    Zipcode VARCHAR(7)
);

CREATE TABLE staff (
    code INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20) NOT NULL,
    Password VARCHAR(30) NOT NULL
);

-- categoriesテーブル
CREATE TABLE categories (
    id INT PRIMARY KEY,
    category_id INT UNIQUE NOT NULL,
    category_name VARCHAR(255) NOT NULL
);

-- productsテーブル
CREATE TABLE products (
    id INT PRIMARY KEY,
    category_id INT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);


CREATE TABLE categories (
    id INT PRIMARY KEY,
    category_id INT(11) NOT NULL,
    category_name VARCHAR(255) NOT NULL
);

-- productsテーブル
CREATE TABLE products (
    id INT PRIMARY KEY,
    category_id VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    image_path VARCHAR(255),
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);
