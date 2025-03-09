<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Store Landing Page</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        /* Remove default body margins */
        body,
        html {
            margin: 0;
            padding: 0;
        }

        /* Custom grid styles for store items */
        .store-grid {
            display: grid;
            /* 5 columns */
            grid-template-columns: repeat(5, 1fr);
            /* Adjust gap as needed */
            gap: 1rem;
        }

        .store-item {
            padding: 1rem;
            text-align: center;
            transition: transform 0.2s ease-in-out;
        }

        .store-item:hover {
            transform: scale(1.02);
        }

        .store-item img {
            max-width: 100%;
            height: auto;
            margin-bottom: 0.5rem;
        }

        /* Footer styling */
        footer {
            padding: 1rem 0;
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <!-- Logo with canaryIcon.svg -->
            <a class="navbar-brand d-flex align-items-center" href="index.html">
                <img src="img/canaryIcon.svg" alt="Logo" width="30" height="30" class="me-2" />
                Canary
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Navigation Links (no ms-auto, so they stay near the brand) -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="store/store.html">Store</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="messaging.html">Messaging</a>
                    </li>
                    <!-- Add more links if needed -->
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content: Store Grid (container-fluid spans the full width) -->
    <div class="container-fluid py-3">
        <div class="store-grid">
            <!-- 20 store items (4 rows x 5 columns) -->
            <div class="store-item">
                <img src="img/gpu1.jpg" alt="Product 1" />
                <h5>RTX 20 & 10 Series Bulk</h5>
                <p>New and Used graphics cards</p>
            </div>
            <div class="store-item">
                <img src="img/gpu7.jpg" alt="Product 2" />
                <h5>Product 2</h5>
            </div>
            <div class="store-item">
                <img src="img/gpu3.jpg" alt="Product 3" />
                <h5>Product 3</h5>
            </div>
            <div class="store-item">
                <img src="img/gpu6.jpg" alt="Product 4" />
                <h5>Product 4</h5>
            </div>
            <div class="store-item">
                <img src="img/gpu5.jpg" alt="Product 5" />
                <h5>Product 5</h5>
            </div>
            <!-- Row 2 -->
            <div class="store-item">
                <img src="img/gpu4.jpg" alt="Product 6" />
                <h5>Product 6</h5>
            </div>
            <div class="store-item">
                <img src="img/gpu5.jpg" alt="Product 7" />
                <h5>Product 7</h5>
            </div>
            <div class="store-item">
                <img src="img/gpu8.jpg" alt="Product 8" />
                <h5>Product 8</h5>
            </div>
            <div class="store-item">
                <img src="img/gpu2.jpg" alt="Product 9" />
                <h5>Product 9</h5>
            </div>
            <div class="store-item">
                <img src="img/gpu9.jpg" alt="Product 10" />
                <h5>Product 10</h5>
            </div>
        </div>
    </div>

    <!-- Footer (also container-fluid for consistency) -->
    <footer class="container-fluid text-center">
        <p>&copy; 2025 Canary. All Rights Reserved.</p>
        <ul class="list-inline">
            <li class="list-inline-item"><a href="about.html">About Me</a></li>
            <li class="list-inline-item"><a href="contact.html">Contact</a></li>
            <li class="list-inline-item"><a href="privacy.html">Privacy Policy</a></li>
        </ul>
    </footer>

    <!-- Bootstrap 5 JS (for responsive navbar) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>