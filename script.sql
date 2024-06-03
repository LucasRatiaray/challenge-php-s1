CREATE TABLE User (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Page (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES User(id)
);

CREATE TABLE Comment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    page_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES User(id)
);

CREATE TABLE Roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
    type ENUM('admin', 'modérateur', 'user')

);

CREATE TABLE config (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cle VARCHAR(255) NOT NULL,
    valeur TEXT,
    type VARCHAR(50),
    description TEXT
);


CREATE TABLE medias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    type VARCHAR(50),
    chemin VARCHAR(255),
    date_creation DATETIME,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES User(id)

);