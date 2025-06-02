<?php
session_start();
require_once '../../config/config.php';

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login/login.php");
    exit;
}

// Define variables and initialize with empty values
$title = $description = $price = $location = $email_contact_detail = $cell_contact_detail = "";
$image1_path = $image2_path = $image3_path = null; // Initialize image paths as null

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]); // Description should be a string
    $price = filter_var($_POST["price"], FILTER_VALIDATE_FLOAT);
    $location = trim($_POST["location"]);
    $email_contact_detail = trim($_POST["email_contact_detail"]);
    $cell_contact_detail = trim($_POST["cell_contact_detail"]);

    // Basic validation
    if (empty($title) || empty($description) || $price === false || $price <= 0 || empty($location)) {
        $_SESSION['upload_message'] = "Please fill in all required fields correctly (Title, Description, Price, Location).";
        $_SESSION['upload_message_type'] = "danger";
        header("location: add-listing.php");
        exit;
    }

    $user_id = $_SESSION["id"]; // Get user_id from session

    // Handle image uploads
    $target_dir = "../../uploads/"; // Directory where images will be saved
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Create directory if it doesn't exist
    }

    $uploaded_image_paths = [];
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $max_file_size = 5 * 1024 * 1024; // 5MB

    if (isset($_FILES['images']['name']) && is_array($_FILES['images']['name'])) {
        $total_files = count($_FILES['images']['name']);
        if ($total_files > 3) {
            $_SESSION['upload_message'] = "You can upload a maximum of 3 images.";
            $_SESSION['upload_message_type'] = "danger";
            header("location: add-listing.php");
            exit;
        }

        for ($i = 0; $i < $total_files; $i++) {
            $file_name = $_FILES['images']['name'][$i];
            $file_tmp = $_FILES['images']['tmp_name'][$i];
            $file_size = $_FILES['images']['size'][$i];
            $file_error = $_FILES['images']['error'][$i];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($file_error === 0) {
                if ($file_size < $max_file_size) {
                    if (in_array($file_ext, $allowed_extensions)) {
                        $new_file_name = uniqid('', true) . '.' . $file_ext;
                        $file_destination = $target_dir . $new_file_name;

                        if (move_uploaded_file($file_tmp, $file_destination)) {
                            $uploaded_image_paths[] = 'uploads/' . $new_file_name; // Store path relative to root
                        } else {
                            $_SESSION['upload_message'] = "Failed to upload image " . htmlspecialchars($file_name) . ".";
                            $_SESSION['upload_message_type'] = "danger";
                            header("location: add-listing.php");
                            exit;
                        }
                    } else {
                        $_SESSION['upload_message'] = "File type not allowed for " . htmlspecialchars($file_name) . ". Only JPG, JPEG, PNG, GIF are permitted.";
                        $_SESSION['upload_message_type'] = "danger";
                        header("location: add-listing.php");
                        exit;
                    }
                } else {
                    $_SESSION['upload_message'] = "File size too large for " . htmlspecialchars($file_name) . ". Max 5MB.";
                    $_SESSION['upload_message_type'] = "danger";
                    header("location: add-listing.php");
                    exit;
                }
            } else if ($file_error !== 4) { // Error 4 means no file uploaded
                $_SESSION['upload_message'] = "Error uploading " . htmlspecialchars($file_name) . ": Code " . $file_error . ".";
                $_SESSION['upload_message_type'] = "danger";
                header("location: add-listing.php");
                exit;
            }
        }
    }

    // Assign uploaded image paths
    $image1_path = $uploaded_image_paths[0] ?? null;
    $image2_path = $uploaded_image_paths[1] ?? null;
    $image3_path = $uploaded_image_paths[2] ?? null;

    // Prepare an insert statement
    $sql = "INSERT INTO listings (user_id, title, description, price, location, image1_path, image2_path, image3_path, email_contact_detail, cell_contact_detail, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', NOW(), NOW())";

    if ($stmt = $mysqli->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        // CORRECTED TYPE STRING: i (user_id), s (title), s (description), d (price), s (location), s (img1), s (img2), s (img3), s (email), s (cell)
        $stmt->bind_param("issdssssss",
            $user_id,
            $title,
            $description,
            $price,
            $location,
            $image1_path,
            $image2_path,
            $image3_path,
            $email_contact_detail,
            $cell_contact_detail
        );

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            $_SESSION['upload_message'] = "Listing uploaded successfully!";
            $_SESSION['upload_message_type'] = "success";
            // Set a session variable to indicate successful upload
            $_SESSION['upload_success'] = true; // NEW LINE
            header("location: add-listing.php"); // Redirect back to the form page
            exit;
        } else {
            $_SESSION['upload_message'] = "Error: Could not upload listing. " . $stmt->error;
            $_SESSION['upload_message_type'] = "danger";
            $_SESSION['upload_success'] = false; // NEW LINE
        }

        // Close statement
        $stmt->close();
    } else {
        $_SESSION['upload_message'] = "Error: Could not prepare statement. " . $mysqli->error;
        $_SESSION['upload_message_type'] = "danger";
        $_SESSION['upload_success'] = false; // NEW LINE
    }

    // Close connection
    $mysqli->close();

    // Redirect back to upload form if there was an error
    header("location: add-listing.php");
    exit;
}
?>