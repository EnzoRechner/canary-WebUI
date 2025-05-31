<?php
session_start();
require_once '../../config/config.php';

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["id"])) {
    $_SESSION['upload_message'] = "You must be logged in to add a listing.";
    $_SESSION['upload_message_type'] = "danger";
    header("location: ../login/login.php");
    exit;
}

$user_id = $_SESSION["id"];

// Define upload directory relative to project root
// Ensure 'uploads/listings/' directory exists and is writable
$upload_dir = '../../uploads/listings/';

// Create the directory if it doesn't exist
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Initialize variables for form data and errors
$title = $description = $price = $location = "";
$image_paths = [];
$errors = [];

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate Title
    if (empty(trim($_POST["title"]))) {
        $errors[] = "Please enter a title for the listing.";
    } else {
        $title = trim($_POST["title"]);
    }

    // Validate Description
    if (empty(trim($_POST["description"]))) {
        $errors[] = "Please enter a description.";
    } else {
        $description = trim($_POST["description"]);
    }

    // Validate Price
    if (empty(trim($_POST["price"]))) {
        $errors[] = "Please enter a price.";
    } elseif (!is_numeric($_POST["price"]) || $_POST["price"] <= 0) {
        $errors[] = "Price must be a positive number.";
    } else {
        $price = trim($_POST["price"]);
    }

    // Validate Location
    if (empty(trim($_POST["location"]))) {
        $errors[] = "Please enter a location.";
    } else {
        $location = trim($_POST["location"]);
    }

    // Handle Image Uploads
    if (isset($_FILES['images']) && count($_FILES['images']['name']) > 0) {
        $total_files = count($_FILES['images']['name']);
        if ($total_files > 3) {
            $errors[] = "You can upload a maximum of 3 images.";
        } else {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_file_size = 5 * 1024 * 1024; // 5MB

            for ($i = 0; $i < $total_files; $i++) {
                $file_name = $_FILES['images']['name'][$i];
                $file_tmp = $_FILES['images']['tmp_name'][$i];
                $file_size = $_FILES['images']['size'][$i];
                $file_type = $_FILES['images']['type'][$i];
                $file_error = $_FILES['images']['error'][$i];

                if ($file_error !== UPLOAD_ERR_OK) {
                    $errors[] = "Image " . ($i + 1) . " upload error: " . $file_error_messages[$file_error];
                    continue;
                }
                if (!in_array($file_type, $allowed_types)) {
                    $errors[] = "Image " . ($i + 1) . ": Only JPG, PNG, and GIF files are allowed.";
                    continue;
                }
                if ($file_size > $max_file_size) {
                    $errors[] = "Image " . ($i + 1) . " size exceeds 5MB limit.";
                    continue;
                }

                $extension = pathinfo($file_name, PATHINFO_EXTENSION);
                $new_file_name = uniqid('listing_', true) . '.' . $extension;
                $target_file_path = $upload_dir . $new_file_name;

                if (move_uploaded_file($file_tmp, $target_file_path)) {
                    // Store path relative to project root (e.g., 'uploads/listings/filename.jpg')
                    $image_paths[] = 'uploads/listings/' . $new_file_name;
                } else {
                    $errors[] = "Failed to upload image " . ($i + 1) . ".";
                }
            }
        }
    } else {
        $errors[] = "Please upload at least one image.";
    }

    // If no validation errors, proceed to insert into database
    if (empty($errors)) {
        // Prepare image paths for database insertion (handle up to 3 images)
        $img1 = $image_paths[0] ?? null;
        $img2 = $image_paths[1] ?? null;
        $img3 = $image_paths[2] ?? null;

        $sql = "INSERT INTO listings (user_id, title, description, price, location, image1_path, image2_path, image3_path, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active')";

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("isdsssss", $user_id, $title, $description, $price, $location, $img1, $img2, $img3);

            if ($stmt->execute()) {
                $_SESSION['upload_message'] = "Listing uploaded successfully!";
                $_SESSION['upload_message_type'] = "success";
                header("location: ../store/store.php"); // Redirect to store page
                exit;
            } else {
                $_SESSION['upload_message'] = "Error: Could not add listing. " . $mysqli->error;
                $_SESSION['upload_message_type'] = "danger";
            }
            $stmt->close();
        } else {
            $_SESSION['upload_message'] = "Error: Database statement preparation failed. " . $mysqli->error;
            $_SESSION['upload_message_type'] = "danger";
        }
    } else {
        // Store errors in session to display on add-listing.php
        $_SESSION['upload_message'] = implode("<br>", $errors);
        $_SESSION['upload_message_type'] = "danger";
    }

    $mysqli->close();
    // Redirect back to add-listing.php to show messages or if errors occurred
    header("location: add-listing.php");
    exit;
}
?>