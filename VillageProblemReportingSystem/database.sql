

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    mobile VARCHAR(15),
    email VARCHAR(100),
    password VARCHAR(255),
    village VARCHAR(100),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    complaint_id VARCHAR(20),
    user_id INT,
    title VARCHAR(150),
    category VARCHAR(100),
    description TEXT,
    location VARCHAR(150),
    landmark VARCHAR(150),
    priority VARCHAR(50),
    image VARCHAR(255),
    contact VARCHAR(15),
    status VARCHAR(50) DEFAULT 'Pending',
    admin_remarks TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    complaint_id VARCHAR(20),
    user_id INT,
    rating INT,
    feedback TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default admin credentials
-- For a real project, always store hashed passwords. Keeping plain text here for college project simplicity.
INSERT INTO admin (username, password) VALUES ('admin', 'admin123');
