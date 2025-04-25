<?php
$host = '127.0.0.1';
$dbname = 'web_db';
$username = 'root'; // Default XAMPP username
$password = '';     // Default XAMPP password (empty)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Optional: Confirm connection (for debugging)
    // echo "Database connected successfully!";
} catch (PDOException $e) {
    // Output the error and stop execution
    die("Connection failed: " . $e->getMessage());
}
?>
