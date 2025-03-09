<?php
include "includes/db.php"; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $hashedPasswordClient = $_POST['hashedPassword']; // Already hashed with SHA-256 in JS

    // Hash again using bcrypt for extra security
    $finalPasswordHash = password_hash($hashedPasswordClient, PASSWORD_BCRYPT);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $finalPasswordHash);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success text-center'>User registered successfully!</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>Error: " . $stmt->error . "</div>";
    }
}
?>
