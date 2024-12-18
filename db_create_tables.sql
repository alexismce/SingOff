CREATE TABLE users (
    id INT PRIMARY KEY IDENTITY(1,1),
    username NVARCHAR(50) NOT NULL UNIQUE,
    password NVARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT GETDATE()
);

CREATE TABLE sign_off_process (
    id INT PRIMARY KEY IDENTITY(1,1),
    user_id INT NOT NULL,
    process_details NVARCHAR(MAX),
    created_at DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE inventory (
    id INT PRIMARY KEY IDENTITY(1,1),
    device_name NVARCHAR(100) NOT NULL,
    quantity INT NOT NULL,
    updated_at DATETIME DEFAULT GETDATE()
);

CREATE TABLE provisioning (
    id INT PRIMARY KEY IDENTITY(1,1),
    device_id INT NOT NULL,
    user_id INT NOT NULL,
    status NVARCHAR(50),
    created_at DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (device_id) REFERENCES inventory(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE testing (
    id INT PRIMARY KEY IDENTITY(1,1),
    device_id INT NOT NULL,
    user_id INT NOT NULL,
    test_results NVARCHAR(MAX),
    created_at DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (device_id) REFERENCES inventory(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE tracking (
    id INT PRIMARY KEY IDENTITY(1,1),
    shipment_id NVARCHAR(50) NOT NULL,
    status NVARCHAR(50),
    updated_at DATETIME DEFAULT GETDATE()
);
