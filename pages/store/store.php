<?php
// Start the session
session_start();

// Include the database configuration file
require_once '../../config/config.php'; // Adjust path based on your file structure

// Check if the user is logged in for navbar display
$loggedIn = isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;
$userName = $loggedIn ? htmlspecialchars($_SESSION["username"]) : ""; // Get username if logged in
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canary Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

        .dark-mode {
            background-color: #212529;
            color: white;
        }

        .navbar,
        .footer {
            background: white;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .dark-mode .navbar,
        .dark-mode .nav-item,
        .dark-mode .footer {
            background: #343a40;
            color: white;
        }

        .product-card {
            border-radius: 12px; /* Corrected typo: should be single value or shorthand */
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
            background: white;
            display: flex; /* Make card content a flex container */
            flex-direction: column; /* Stack children vertically */
        }
        .product-card .card-img-top {
            width: 100%;
            height: 200px; /* Fixed height for images */
            object-fit: cover; /* Cover the area, crop if necessary */
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }


        .dark-mode .product-card {
            background: #2d2f31;
            color: white;
        }

        .product-card:hover {
            transform: scale(1.02);
        }

        .filter-container {
            padding: 20px;
            border-radius: 8px;
            background: white;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); /* Added box-shadow for consistency */
        }

        .dark-mode .filter-container {
            background: #2d2f31;
            color: white;
        }

        .footer {
            padding: 15px;
            text-align: center;
        }

        .dark-mode .list-group-item {
            background: #2d2f31;
            color: white;
        }

        .hero-section {
            height: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background: linear-gradient(135deg,rgb(255, 115, 0),rgb(255, 153, 0));
            color: white;
            border-radius: 0 0 20px 20px;
            padding: 50px;
        }
        .btn-custom {
            background-color:rgb(255, 156, 8);
            color: black;
            font-size: 1rem;
            padding: 10px 20px;
            border-radius: 8px;
            transition: 0.3s;
        }
        .btn-custom:hover {
            background-color: black;
            color: white;
        }
        .btn .dark-mode {
            background:rgb(0, 128, 255);
        }
        .filterText{
            color: black;
        }
        .dark-mode .filterText {
            color: white; /* Ensure text is visible in dark mode */
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
        .card-body {
            flex-grow: 1; /* Allow card body to take up available space */
            display: flex;
            flex-direction: column;
        }
        .card-text {
            flex-grow: 1; /* Allow description to grow */
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="../../index.php"> <img src="../../favicon.ico" alt="Logo" width="40" height="40"> Canary
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="store.php">Shop</a></li> <?php if ($loggedIn): ?>
                        <li class="nav-item welcome-message">Welcome back, <?php echo $userName; ?>!</li>
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
    <div class="hero-section">
        <div class="container">
            <h1>Canary Shop</h1>
            <p class="lead">Discover the latest GPU listings.</p>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="filter-container p-3 shadow-sm">
                    <h5 class="filterText">Filter by Category</h5>
                    <ul class="list-group">
                        <li class="list-group-item"><a href="#" class="text-decoration-none">All</a></li>
                        <li class="list-group-item"><a href="#" class="text-decoration-none">Graphics Cards</a></li>
                        <li class="list-group-item"><a href="#" class="text-decoration-none">Laptops</a></li>
                        <li class="list-group-item"><a href="#" class="text-decoration-none">Monitors</a></li>
                        <li class="list-group-item"><a href="#" class="text-decoration-none">Accessories</a></li>
                    </ul>
                    <div class="mt-3">
                        <a href="../../index.php" class="btn btn-custom">Home</a> </div>
                    <div class="mt-3">
                        <a href="../add-listing/add-listing.php" class="btn btn-custom">Add Listing</a>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="row">
                    <?php
                    // Prepare a select statement to fetch active listings
                    // Only fetch listings with status 'active'
                    $sql = "SELECT id, title, description, price, image1_path FROM listings WHERE status = 'active' ORDER BY created_at DESC";

                    if ($stmt = $mysqli->prepare($sql)) {
                        if ($stmt->execute()) {
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                // Loop through result set and display each listing
                                while ($row = $result->fetch_assoc()) {
                                    // Construct image path.
                                    // Assuming image1_path in DB is relative to project root (e.g., 'img/gpu1.jpg').
                                    // Since store.php is in pages/store/, it needs '../../' to go back to root.
                                    $imagePath = '../../' . htmlspecialchars($row['image1_path']);
                                    $listingPrice = number_format($row['price'], 2, '.', ','); // Format price for ZAR (e.g., 1,599.99)
                                    $description_display = htmlspecialchars(substr($row['description'], 0, 100));
                                    if (strlen($row['description']) > 100) {
                                        $description_display .= '...'; // Add ellipsis if description is truncated
                                    }

                                    echo '
                                    <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                                        <div class="card product-card h-100">
                                            <img src="' . $imagePath . '" class="card-img-top" alt="' . htmlspecialchars($row['title']) . '">
                                            <div class="card-body d-flex flex-column">
                                                <h5 class="card-title">' . htmlspecialchars($row['title']) . '</h5>
                                                <p class="card-text flex-grow-1">' . $description_display . '</p>
                                                <p class="card-text fw-bold">R' . $listingPrice . '</p>
                                                <a href="#" class="btn btn-custom mt-auto">View Details</a> </div>
                                        </div>
                                    </div>';
                                }
                            } else {
                                echo '<div class="col-12"><p class="text-center alert alert-info">No active listings found. Be the first to add one!</p></div>';
                            }
                        } else {
                            echo '<div class="col-12"><p class="text-center alert alert-danger">Error fetching listings: ' . $mysqli->error . '</p></div>';
                        }
                        $stmt->close();
                    } else {
                        echo '<div class="col-12"><p class="text-center alert alert-danger">Database query preparation failed: ' . $mysqli->error . '</p></div>';
                    }

                    // Close connection
                    $mysqli->close();
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="footer mt-4">
        <p>&copy; <?php echo date("Y"); ?> Canary. All Rights Reserved.</p>
        <p>
            <a href="https://www.flaticon.com/free-icons/canary" title="Canary icons">Canary icons created by Freepik - Flaticon</a>
        </p>
    </div>

</body>

</html>