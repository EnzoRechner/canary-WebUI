<?php
session_start();
require_once "../../includes/db.php";

// CSRF Protection
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token validation failed');
    }

    $username = sanitize_input($conn, $_POST['username']);
    $email = sanitize_input($conn, $_POST['email']);
    $hashedPasswordClient = $_POST['hashedPassword'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Invalid email format');
    }

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die('Username or email already exists');
    }

    // Generate verification token
    $verificationToken = bin2hex(random_bytes(32));
    $tokenExpiry = date('Y-m-d H:i:s', strtotime('+24 hours'));

    // Hash password with bcrypt
    $finalPasswordHash = password_hash($hashedPasswordClient, PASSWORD_BCRYPT, ['cost' => 12]);

    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, email_verified, reset_token, reset_token_expiry) VALUES (?, ?, ?, 0, ?, ?)");
    $stmt->bind_param("sssss", $username, $email, $finalPasswordHash, $verificationToken, $tokenExpiry);

    if ($stmt->execute()) {
        // Send verification email
        $verificationLink = "http://" . $_SERVER['HTTP_HOST'] . "/verify.php?token=" . $verificationToken;
        $to = $email;
        $subject = "Verify your email address";
        $message = "Please click the following link to verify your email address: " . $verificationLink;
        $headers = "From: noreply@canary.com";

        mail($to, $subject, $message, $headers);

        echo json_encode(['success' => true, 'message' => 'Registration successful! Please check your email to verify your account.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Registration failed. Please try again.']);
    }
    exit;
}
?>