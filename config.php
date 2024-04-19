<?php

// Database configuration
define('DB_HOST', 'localhost'); // Change 'localhost' to your database host if it's different
define('DB_USER', 'username');  // Change 'username' to your database username
define('DB_PASS', 'password');  // Change 'password' to your database password if you have one
define('DB_NAME', 'glacialblades'); // Change 'glacialblades' to your database name

// Establish a database connection using PDO
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If connection fails, handle the error
    die("Connection failed: " . $e->getMessage());
}

?>
