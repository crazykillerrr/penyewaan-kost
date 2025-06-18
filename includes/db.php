<?php
// Database configuration
$host = 'localhost';
$dbname = 'penyewaan_kost';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Create database and tables if they don't exist
function createDatabase() {
    global $pdo;
    
    // Create tables
    $sql = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        phone VARCHAR(20),
        profile_image VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS kamar (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama_kamar VARCHAR(100) NOT NULL,
        deskripsi TEXT,
        harga_per_bulan DECIMAL(10,2) NOT NULL,
        fasilitas TEXT,
        foto VARCHAR(255),
        status ENUM('tersedia', 'disewa', 'maintenance') DEFAULT 'tersedia',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS sewa (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        kamar_id INT NOT NULL,
        tanggal_mulai DATE NOT NULL,
        tanggal_selesai DATE NOT NULL,
        total_harga DECIMAL(10,2) NOT NULL,
        status ENUM('pending', 'approved', 'rejected', 'active', 'completed') DEFAULT 'pending',
        catatan TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (kamar_id) REFERENCES kamar(id) ON DELETE CASCADE
    );

    INSERT IGNORE INTO admins (username, password) VALUES ('admin', '" . password_hash('admin123', PASSWORD_DEFAULT) . "');
    ";
    
    $pdo->exec($sql);
}

createDatabase();
?>
