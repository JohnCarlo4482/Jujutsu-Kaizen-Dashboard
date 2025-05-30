CREATE DATABASE kaye;
USE kaye;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email varchar(255),
    username varchar(255),
    password varchar(255),
    user_id int(25),
    school_id varchar(255)
);
