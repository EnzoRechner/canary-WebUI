<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Listing</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
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
    </style>
</head>
<body>
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
                    <li class="nav-item"><a class="nav-link" href="../store/store.php">Shop</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.html">Register</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.html">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.html">About</a></li>
                    <li class="nav-item"><button class="btn btn-outline-secondary"
                            onclick="toggleDarkMode()">🌕</button></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h2 class="text-center">Upload a New Listing</h2>
        
        <form action="upload.php" method="POST" enctype="multipart/form-data" class="p-4 border rounded shadow">
            <!-- Title -->
            <div class="mb-3">
                <label for="title" class="form-label">Listing Title</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Enter title" required>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter a brief description" required></textarea>
            </div>

            <!-- Price -->
            <div class="mb-3">
                <label for="price" class="form-label">Price ($)</label>
                <input type="number" class="form-control" id="price" name="price" placeholder="Enter price" step="0.01" required>
            </div>

            <!-- Image Upload -->
            <div class="mb-3">
                <label for="images" class="form-label">Upload Images</label>
                <input type="file" class="form-control" id="images" name="images[]" multiple required>
                <small class="text-muted">You can upload multiple images (JPG, PNG, GIF).</small>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-100">Upload Listing</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
