<?php
include "includes/db.php"; // Connect to MySQL
session_start();

// Ensure user is logged in (replace with your authentication check)
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to upload a listing.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $user_id = $_SESSION['user_id'];

    // Handle image uploads
    $target_dir = "uploads/";
    $imagePaths = [];

    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        $image_name = basename($_FILES['images']['name'][$key]);
        $target_file = $target_dir . time() . "_" . $image_name; // Prevent duplicates

        if (move_uploaded_file($_FILES['images']['tmp_name'][$key], $target_file)) {
            $imagePaths[] = $target_file;
        }
    }

    // Convert array of images to a JSON string (to store multiple images in one field)
    $imagePathsJson = json_encode($imagePaths);

    // Insert listing into database
    $stmt = $conn->prepare("INSERT INTO listings (user_id, title, description, price, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $title, $description, $price, $imagePathsJson);

    if ($stmt->execute()) {
        echo "Listing uploaded successfully!";
    } else {
        echo "Error uploading listing.";
    }
}
?>