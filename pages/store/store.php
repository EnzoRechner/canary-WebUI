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
            border-radius: 12px, 12px, 12px, 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
            background: white;
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
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="../../index.php">
                <img src="../../img/canaryIcon.png" alt="Logo" width="40" height="40"> Canary
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="shop.html">Shop</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.html">Register</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.html">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.html">About</a></li>
                    <li class="nav-item"><button class="btn btn-outline-secondary"
                            onclick="toggleDarkMode()">🌕</button></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="hero-section">
    <!-- Main Shop Layout -->
    <div class="container mt-4">
        <div class="row">
            <!-- Filters (Left Sidebar) -->
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
                        <a href="../../index.php" class="btn btn-custom">Home</a>
                    </div>
                    <div class="mt-3">
                        <a href="../add-listing/add-listing.php" class="btn btn-custom">Add Listing</a>
                    </div>
                </div>
            </div>

            <!-- Products (Right Grid) -->
            <div class="col-md-9">
                <div class="row">
                    <!-- Demo Product 1 -->
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card product-card">
                            <img src="../../img/gpu1.jpg" class="card-img-top" alt="Product">
                            <div class="card-body">
                                <h5 class="card-title">RTX 4090</h5>
                                <p class="card-text">Powerful GPU for gaming.</p>
                                <p class="card-text">$1,599.99</p>
                                <a href="#" class="btn btn-custom">View</a>
                            </div>
                        </div>
                    </div>

                    <!-- Demo Product 2 -->
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card product-card">
                            <img src="../../img/gpu2.jpg" class="card-img-top" alt="Product">
                            <div class="card-body">
                                <h5 class="card-title">Gaming Laptop</h5>
                                <p class="card-text">High-performance laptop.</p>
                                <p class="card-text">$2,299.99</p>
                                <a href="#" class="btn btn-custom">View</a>
                            </div>
                        </div>
                    </div>

                    <!-- Demo Product 3 -->
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card product-card">
                            <img src="../../img/gpu7.jpg" class="card-img-top" alt="Product">
                            <div class="card-body">
                                <h5 class="card-title">UltraWide Monitor</h5>
                                <p class="card-text">34-inch curved display.</p>
                                <p class="card-text">$899.99</p>
                                <a href="#" class="btn btn-custom">View</a>
                            </div>
                        </div>
                    </div>

                    <!-- Demo Product 4 -->
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card product-card">
                            <img src="../../img/gpu5.jpg" class="card-img-top" alt="Product">
                            <div class="card-body">
                                <h5 class="card-title">Mechanical Keyboard</h5>
                                <p class="card-text">RGB backlit keyboard.</p>
                                <p class="card-text">$129.99</p>
                                <a href="#" class="btn btn-custom">View</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Footer -->
    <div class="footer mt-4">
        <p>&copy; 2025 Canary. All Rights Reserved.</p>
        <p>
            <a href="https://www.flaticon.com/free-icons/canary" title="Canary icons">Canary icons created by Freepik - Flaticon</a>
        </p>
    </div>

</body>

</html>