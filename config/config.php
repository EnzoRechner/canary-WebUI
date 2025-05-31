<?php
// Database credentials for if0_39033426_CanaryTest

// Define constants for database connection
define('DB_SERVER', 'sql204.infinityfree.com'); // Your database host
define('DB_USERNAME', 'if0_39033426'); // Your database username
define('DB_PASSWORD', 'VN0bxkFYTMK2IZ'); // **CHANGE THIS TO YOUR ACTUAL DATABASE PASSWORD**
define('DB_NAME', 'if0_39033426_CanaryTest'); // Your database name

// Attempt to establish a database connection
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($mysqli->connect_error) {
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}
?>