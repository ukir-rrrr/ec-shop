CREATE DATABASE ecshop_db default character SET utf8;

use ecshop_db;

grant all privileges on ecshop_db. *to ecshop_user@'localhost' identified by 'ecshop_pass' with grant option;


CREATE TABLE Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Address TEXT
    Phone VARCHAR(20),
    Last_name VARCHAR(50),
    First_name VARCHAR(50),
    Last_name_kana VARCHAR(50),
    First_name_kana VARCHAR(50),
    Zipcode VARCHAR(7)
);
