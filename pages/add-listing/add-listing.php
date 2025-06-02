<?php
session_start();
require_once '../../config/config.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login/login.php");
    exit;
}

$loggedIn = true;
$userName = htmlspecialchars($_SESSION["username"]);

// --- START NEW LOGIC FOR RESETTING MESSAGES AND BUTTON STATE ---
// Check if the page was *not* just redirected from a form submission (i.e., it's a fresh load)
// This assumes 'upload.php' always redirects to 'upload_listing.php'
// And if a form is submitted, $_SERVER["REQUEST_METHOD"] will be "POST" on upload.php, but GET on the redirect back to this page.
// The presence of $_SESSION['upload_message'] indicates a redirect from upload.php.
$showGoToStoreButton = false;

// Show "Go to Store" button only if upload was successful, then reset the flag for next visit
if (isset($_SESSION['upload_success']) && $_SESSION['upload_success'] === true) {
    $showGoToStoreButton = true;
    unset($_SESSION['upload_success']); // Reset so next visit shows "Cancel" and submit button
} else {
    $showGoToStoreButton = false;
}

// Always clear any leftover messages on fresh load
if (!isset($_SESSION['upload_message'])) {
    unset($_SESSION['upload_message_type']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Listing</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script>
        function toggleDarkMode() {
            document.body.classList.toggle("dark-mode");
            localStorage.setItem("darkMode", document.body.classList.contains("dark-mode"));
        }
        document.addEventListener("DOMContentLoaded", function () {
            if (localStorage.getItem("darkMode") === "true") {
                document.body.classList.add("dark-mode");
            }
        });
    </script>
    <style>
        body {
            background-color: #f8f9fa;
            transition: 0.3s;
        }
        .navbar {
            background: white;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .dark-mode {
            background-color: #212529;
            color: white;
        }
        .hero-section {
            height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background: linear-gradient(135deg,rgb(255, 115, 0),rgb(255, 153, 0));
            color: white;
            border-radius: 0 0 20px 20px;
            padding: 50px;
        }
        .hero-section h1 {
            font-size: 3rem;
            font-weight: bold;
        }
        .dark-mode .navbar,
        .dark-mode .footer {
            background: #343a40;
            color: white;
        }
        .btn-custom {
            background-color: rgb(255, 123, 0);
            color: white;
            border-radius: 8px;
            padding: 10px;
            transition: 0.3s;
            height: auto;
            align-content: center;
        }
        .btn-custom:hover {
            background-color: #000000;
            color: white;
        }
        .about-section {
            padding: 60px 20px;
            text-align: center;
        }
        .footer {
            background: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0px -4px 10px rgba(0, 0, 0, 0.1);
        }
        .navbar-nav .nav-item.welcome-message {
            display: flex;
            align-items: center;
            padding: .5rem 1rem;
            color:rgb(0, 0, 0); /* Changed to black for contrast in light mode */
            font-weight: bold;
        }
        .dark-mode .navbar-nav .nav-item.welcome-message {
            color:rgb(255, 255, 255);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="../../index.php">
                <img src="../../favicon.ico" alt="Logo" width="40" height="40"> Canary
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="../store/store.php">Shop</a></li>
                    <?php if ($loggedIn): ?>
                        <li class="nav-item welcome-message">Welcome, <?php echo $userName; ?>!</li>
                        <li class="nav-item"><a class="nav-link" href="../login/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="../login/register.php">Register</a></li>
                        <li class="nav-item"><a class="nav-link" href="../login/login.php">Login</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><button class="btn btn-outline-secondary"
                                    onclick="toggleDarkMode()">🌕</button></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h2 class="text-center">Upload a New Listing</h2>

        <?php
        // Display messages if they exist
        if (isset($_SESSION['upload_message'])) {
            echo '<div class="alert ' . ($_SESSION['upload_message_type'] == 'success' ? 'alert-success' : 'alert-danger') . ' text-center">' . $_SESSION['upload_message'] . '</div>';
            // Unset the message immediately after displaying it
            unset($_SESSION['upload_message']);
            unset($_SESSION['upload_message_type']);
        }
        ?>

        <form action="upload.php" method="POST" enctype="multipart/form-data" class="p-4 border rounded shadow">
            <div class="mb-3">
                <label for="title" class="form-label">Listing Title</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Enter title" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="e.g., A brief overview of the item, its condition, and any key features you want to highlight."></textarea>
                <small class="text-muted">Provide a detailed description of your item (e.g., condition, features, history).</small>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price (R)</label>
                <input type="number" class="form-control" id="price" name="price" placeholder="Enter price in R" step="0.01" required>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location" placeholder="e.g., Cape Town, Johannesburg" required>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="email_contact_detail" class="form-label">Email Contact (Optional)</label>
                    <input type="email" class="form-control" id="email_contact_detail" name="email_contact_detail" placeholder="Enter email address">
                    <small class="text-muted">Your email will only be visible to logged-in users viewing your listing.</small>
                </div>
                <div class="col-md-6">
                    <label for="cell_contact_detail" class="form-label">Cell Contact (Optional)</label>
                    <input type="tel" class="form-control" id="cell_contact_detail" name="cell_contact_detail" placeholder="e.g., +27 82 123 4567">
                    <small class="text-muted">Your cell number will only be visible to logged-in users viewing your listing.</small>
                </div>
            </div>
            <div class="mb-3">
                <label for="images" class="form-label">Upload Images (Max 3)</label>
                <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*" required>
                <small class="text-muted">You can upload up to 3 images (JPG, PNG, GIF).</small>
            </div>

            <?php if ($showGoToStoreButton): // Show "Go to Store" button after successful upload ?>
                <a href="../store/store.php" class="btn btn-custom w-100 mb-3">Go to Store</a>
            <?php else: // Otherwise, show "Upload Listing" button and "Cancel" ?>
                <button type="submit" class="btn btn-custom w-100">Upload Listing</button>
                <hr>
                <a href="../store/store.php" class="btn btn-secondary w-100 justify-content-center">Cancel</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="footer mt-4 text-center">
        <p>&copy; <?php echo date("Y"); ?> Canary. All Rights Reserved.</p>
        <p>
            <a href="https://www.flaticon.com/free-icons/canary" title="Canary icons">
                Canary icons created by Freepik Flaticon
            </a>
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>