CREATE TABLE Payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    paymentDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    paymentExpirationDate TIMESTAMP NOT NULL,
    FOREIGN KEY (userId) REFERENCES Users(id) -- Assuming there is a Users table with a matching id field
);