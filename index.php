<?php
// Start the session
session_start();

// Check if the user is logged in
$loggedIn = isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;
$userName = $loggedIn ? htmlspecialchars($_SESSION["username"]) : ""; // Get username if logged in
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/YOUR-FONT-AWESOME-KEY.js" crossorigin="anonymous"></script> <script>
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
            height: 38vb;
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
            background-color: white;
            color:rgb(255, 156, 8);
            font-size: 1.2rem;
            padding: 15px 30px;
            border-radius: 8px;
            transition: 0.3s;
        }
        .btn-custom:hover {
            background-color: black;
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
        /* Style for the welcome message in navbar */
        .navbar-nav .nav-item.welcome-message {
            display: flex;
            align-items: center;
            padding: .5rem 1rem;
            color:rgb(0, 0, 0); /* Bootstrap success green */
            font-weight: bold;
        }
        .dark-mode .navbar-nav .nav-item.welcome-message {
            color:rgb(255, 255, 255); /* Lighter green for dark mode */
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php"> <img src="favicon.ico" alt="Logo" width="40" height="40"> Canary
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="pages/store/store.php">Shop</a></li>
                    <?php if ($loggedIn): ?>
                        <li class="nav-item welcome-message">Welcome back, <?php echo $userName; ?>!</li>
                        <li class="nav-item"><a class="nav-link" href="pages/login/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="pages/login/register.php">Register</a></li>
                        <li class="nav-item"><a class="nav-link" href="pages/login/login.php">Login</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><button class="btn btn-outline-secondary" onclick="toggleDarkMode()">🌕</button></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container">
            <h1>Canary</h1>
            <p class="lead">Buying and Selling GPUs Exclusively</p>
            <div class="mt-4">
                <a href="pages/store/store.php" class="btn btn-custom">Browse Marketplace</a>
            </div>
        </div>
    </div>

    <div class="about-section" id="about">
        <div class="container">
            <h2>About Us</h2>
            <p>This is a GPU Exclusive Marketplace specializing in<br>
               Fast, Secure and Connected trade of GPUs.<br>
               Registering with us puts you at the forefront of GPU trade.<br>
               Listing your own GPU via the store page.<br>
               Click below to sign up and start trading!<br>
               <div class="mt-4">
                <?php if (!$loggedIn): ?>
                    <a href="pages/login/login.php" class="btn btn-light border">Get Started</a>
                <?php else: ?>
                    <a href="pages/store/store.php" class="btn btn-light border">Go to Store</a>
                <?php endif; ?>
            </div>
            </p>
        </div>
    </div>

    <div class="footer">
        <p>&copy; <?php echo date("Y"); ?> Canary. All Rights Reserved.</p>
        <p>
            <a href="https://www.flaticon.com/free-icons/canary" title="Canary icons">Canary icons created by Freepik - Flaticon</a>
        </p>
    </div>
</body>
</html>