<?php
// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$config = [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'username' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: '',
    'database' => getenv('DB_NAME') ?: 'Canary'
];

try {
    // Create connection using mysqli
    $conn = new mysqli(
        $config['host'],
        $config['username'],
        $config['password'],
        $config['database']
    );

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Set charset to utf8mb4
    $conn->set_charset("utf8mb4");

    // Enable strict mode
    $conn->query("SET SESSION sql_mode = 'STRICT_ALL_TABLES'");
} catch (Exception $e) {
    // Log error and display user-friendly message
    error_log($e->getMessage());
    die("Sorry, there was a problem connecting to the database. Please try again later.");
}

// Function to sanitize input
function sanitize_input($conn, $input) {
    if (is_array($input)) {
        return array_map(function($item) use ($conn) {
            return sanitize_input($conn, $item);
        }, $input);
    }
    return $conn->real_escape_string(trim($input));
}

// Function to validate file upload
function validate_image_upload($file) {
    $allowed_types = ['image/jpeg', 'image/png'];
    $max_size = 2 * 1024 * 1024; // 2MB

    if (!in_array($file['type'], $allowed_types)) {
        return "Invalid file type. Only JPG and PNG are allowed.";
    }

    if ($file['size'] > $max_size) {
        return "File is too large. Maximum size is 2MB.";
    }

    return true;
}