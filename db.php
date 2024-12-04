<?php
$host = 'localhost';  // Database host (usually 'localhost' for local development)
$dbname = 'video_progress_system';  // Replace with your actual database name
$username = 'root';  // Replace with your database username
$password = '';  // Replace with your database password

try {
    // Create a PDO instance to connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Optional: Set default fetch mode to associative arrays
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    // If the connection fails, output an error message
    die("Connection failed: " . $e->getMessage());
}
?>
