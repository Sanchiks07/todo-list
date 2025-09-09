CREATE DATABASE todo_list;

USE todo_list;

CREATE TABLE lists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task VARCHAR(50) NOT NULL,
    description VARCHAR(255) NOT NULL,
    list_id INT,
    due_date DATE NOT NULL,
    FOREIGN KEY (list_id) REFERENCES lists(id)
);