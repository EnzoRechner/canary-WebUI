<?php
session_start();
require_once '../../config/config.php';

$loggedIn = isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;
$userName = $loggedIn ? htmlspecialchars($_SESSION["username"]) : "";

$listing = null;
$seller = null;
$seller_user_id = null; // Variable to store seller's user ID

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $listing_id = $_GET['id'];

    // Updated SQL query to include created_at, updated_at, email_contact_detail, and cell_contact_detail
    $sql = "SELECT l.id, l.title, l.description, l.price, l.location, l.image1_path, l.image2_path, l.image3_path, l.created_at, l.updated_at, l.email_contact_detail, l.cell_contact_detail, u.first_name, u.last_name, u.id AS seller_id
            FROM listings l
            JOIN users u ON l.user_id = u.id
            WHERE l.id = ? AND l.status = 'active'";

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $listing_id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $listing = $result->fetch_assoc();
                $seller = htmlspecialchars($listing['first_name'] . ' ' . $listing['last_name']);
                $seller_user_id = $listing['seller_id']; // Get the seller's user ID
            }
        }
        $stmt->close();
    }
}
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canary - Product Details</title>
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

        /* Hide navbar and footer on small screens */
        @media (max-width: 767.98px) {
            .navbar, .footer, .hero-section {
                display: none !important;
            }
            body {
                padding-bottom: 70px; /* Space for fixed mobile buttons */
            }
        }

        .navbar,
        .footer {
            background: white;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            position: fixed; /* Makes the footer stick to the viewport */
            bottom: 0;       /* Aligns it to the bottom of the viewport */
            left: 0;         /* Aligns it to the left of the viewport */
            width: 100%;     /* Makes it span the full width of the viewport */
            z-index: 1000;   /* Ensures it stays on top of other content, adjust as needed */
        }

        .dark-mode .navbar,
        .dark-mode .nav-item,
        .dark-mode .footer {
            background: #343a40;
            color: white;
        }

        .product-detail-card {
            border-radius: 12px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            background: white;
            display: flex; /* Enable flexbox for inner layout */
            flex-wrap: wrap; /* Allow wrapping on smaller screens */
        }

        .dark-mode .product-detail-card {
            background: #2d2f31;
            color: white;
        }

        .product-image-container {
            flex: 1; /* Take up available space */
            min-width: 300px; /* Minimum width for image container */
            background-color: black; /* Black background for blank space */
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
            padding: 20px; /* Padding inside the image container */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 767.98px) {
            .product-image-container {
                border-bottom-left-radius: 0;
                border-top-right-radius: 12px;
                width: 100%; /* Full width on small screens */
            }
        }

        .product-detail-info {
            flex: 1.5; /* Take up more space than image */
            padding: 20px;
        }

        @media (max-width: 767.98px) {
            .product-detail-info {
                width: 100%; /* Full width on small screens */
                padding-top: 0;
            }
        }
        .product-detail-card .carousel,
        .product-detail-card .carousel-inner {
            height: 300px; /* Set a fixed height for the carousel and its inner container */
            width: 100%; /* Ensure it takes full width within its parent */
            overflow: hidden; /* Add this explicitly */
        }
        
        .product-detail-card .carousel-item {
          height: 100%; /* Ensure carousel items fill the height */
          align-items: center; /* Center vertically */
          justify-content: center; /* Center horizontally */
        }

        .product-detail-card .carousel-item img {
            display: block; /* Ensures the image is a block-level element */
            margin: auto;   /* Centers the image horizontally */
            max-width: 100%; /* Ensure image doesn't overflow its item's width */
            max-height: 100%; /* Ensure image doesn't overflow its item's height */
            object-fit: contain; /* Shrink to fit, maintain aspect ratio, show full image */
            border-radius: 8px;
        }

        /* Ensure placeholder image background is also black */
        .product-detail-card .carousel-item img[src*="placehold.co"] {
            background-color: black;
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
        .navbar-nav .nav-item.welcome-message {
            display: flex;
            align-items: center;
            padding: .5rem 1rem;
            color:rgb(0, 0, 0);
            font-weight: bold;
        }
        .dark-mode .navbar-nav .nav-item.welcome-message {
            color:rgb(255, 255, 255);
        }

        /* Fixed mobile navigation */
        .mobile-nav-fixed {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: white;
            box-shadow: 0px -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1030;
            padding: 10px 15px;
            display: flex;
            justify-content: space-around;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .dark-mode .mobile-nav-fixed {
            background-color: #343a40;
        }

        .mobile-nav-fixed .btn {
            flex-grow: 1;
            margin: 0 5px;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg d-none d-md-flex">
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

    <div class="hero-section d-none d-md-flex">
        <div class="container">
            <h1>Product Details</h1>
            <p class="lead">Explore the full details of this listing.</p>
        </div>
    </div>

    <div class="container mt-4 mb-5"> <?php if ($listing): ?>
            <div class="product-detail-card">
                <div class="product-image-container">
                    <div id="productImageCarousel" class="carousel slide w-100" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            $images = [];
                            if (!empty($listing['image1_path'])) $images[] = $listing['image1_path'];
                            if (!empty($listing['image2_path'])) $images[] = $listing['image2_path'];
                            if (!empty($listing['image3_path'])) $images[] = $listing['image3_path'];

                            if (!empty($images)) {
                                foreach ($images as $index => $image_path) {
                                    echo '<div class="carousel-item ' . ($index === 0 ? 'active' : '') . '">';
                                    echo '<img src="../../' . htmlspecialchars($image_path) . '" alt="' . htmlspecialchars($listing['title']) . ' Image ' . ($index + 1) . '">';
                                    echo '</div>';
                                }
                            } else {
                                echo '<div class="carousel-item active">';
                                echo '<img src="https://placehold.co/800x500/000000/cccccc?text=No+Image" alt="No Image Available">'; // Black background for placeholder
                                echo '</div>';
                            }
                            ?>
                        </div>
                        <?php if (count($images) > 1): ?>
                        <button class="carousel-control-prev" type="button" data-bs-target="#productImageCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productImageCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="product-detail-info">
                    <div class="d-flex justify-content-between align-items-center mb-2 flex-column flex-md-row text-center text-md-start">
                        <h2 class="card-title mb-0"><?php echo htmlspecialchars($listing['title']); ?></h2>
                        <p class="text-muted mb-0 mt-2 mt-md-0">Posted: <?php echo date('F j, Y, g:i a', strtotime($listing['created_at'])); ?></p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-column flex-md-row text-center text-md-start">
                        <p class="card-text fs-4 fw-bold mb-0">Price: R<?php echo number_format($listing['price'], 2, '.', ','); ?></p>
                        <p class="card-text mb-0 mt-2 mt-md-0"><strong>Location:</strong> <?php echo htmlspecialchars($listing['location']); ?></p>
                    </div>
                    <p class="card-text text-center text-md-start"><strong>Posted by:</strong> <?php echo $seller; ?></p>
                    <hr>
                    <h5 class="mt-4">Description</h5>
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($listing['description'])); ?></p>
                    
                    <?php if ($loggedIn): ?>
                        <hr>
                        <h5 class="mt-4">Contact Details</h5>
                        <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($listing['email_contact_detail']); ?></p>
                        <p class="card-text"><strong>Cell:</strong> <?php echo htmlspecialchars($listing['cell_contact_detail']); ?></p>
                    <?php else: ?>
                        <hr>
                        <div class="alert alert-info text-center mt-4" role="alert">
                            Please <a href="../login/login.php">login</a> to view contact details.
                        </div>
                    <?php endif; ?>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-start mt-4 d-none d-md-flex">
                        <a href="store.php" class="btn btn-secondary">Back to Shop</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-12 text-center">
                    <div class="alert alert-warning" role="alert">
                        Listing not found or is not active.
                    </div>
                    <a href="store.php" class="btn btn-custom mt-3">Back to Shop</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="mobile-nav-fixed d-flex d-md-none">
        <a href="store.php" class="btn btn-custom">Back to Market</a>
        <a href="../../index.php" class="btn btn-secondary">Home</a>
    </div>

    <div class="footer mt-4 text-center d-none d-md-block">
        <p>&copy; <?php echo date("Y"); ?> Canary. All Rights Reserved.</p>
        <p>
            <a href="https://www.flaticon.com/free-icons/canary" title="Canary icons">Canary icons created by Freepik - Flaticon</a>
        </p>
    </div>

</body>
</html>