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

CREATE TABLE installations (
    id INT PRIMARY KEY IDENTITY(1,1),
    installation_date DATE NOT NULL,
    radio_number NVARCHAR(50) NOT NULL,
    unit_code NVARCHAR(50) NOT NULL,
    x_number NVARCHAR(50) NOT NULL,
    radio_mobile_mid NVARCHAR(50) NOT NULL,
    license_plate NVARCHAR(50) NOT NULL,
    mileage INT NOT NULL,
    make NVARCHAR(50) NOT NULL,
    model NVARCHAR(50) NOT NULL,
    avl1_check NVARCHAR(50) NOT NULL,
    previously_installed NVARCHAR(MAX),
    system_test NVARCHAR(50) NOT NULL,
    installation_type NVARCHAR(50) NOT NULL,
    installer_name NVARCHAR(100) NOT NULL,
    calfire_officer_name NVARCHAR(100) NOT NULL,
    device_data NVARCHAR(MAX),
    installer_signature NVARCHAR(MAX),
    calfire_signature NVARCHAR(MAX),
    created_at DATETIME DEFAULT GETDATE()
);

CREATE TABLE devices (
    id INT PRIMARY KEY IDENTITY(1,1),
    sku NVARCHAR(50) NOT NULL UNIQUE,
    description NVARCHAR(255) NOT NULL
);

CREATE TABLE device_variants (
    id INT PRIMARY KEY IDENTITY(1,1),
    device_id INT NOT NULL,
    variant NVARCHAR(50) NOT NULL,
    FOREIGN KEY (device_id) REFERENCES devices(id)
);

CREATE TABLE installation_devices (
    id INT PRIMARY KEY IDENTITY(1,1),
    installation_id INT NOT NULL,
    device_id INT NOT NULL,
    serial NVARCHAR(100) NOT NULL,
    asset NVARCHAR(100) NOT NULL,
    variant NVARCHAR(50),
    FOREIGN KEY (installation_id) REFERENCES installations(id),
    FOREIGN KEY (device_id) REFERENCES devices(id)
);
