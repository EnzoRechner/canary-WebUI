<?php
// Start a PHP session
session_start();

// Include the database configuration file
require_once '../../config/config.php'; // Adjust path if your config.php is elsewhere

$email = $password = "";
$email_err = $password_err = $login_err = "";

// Check if registration was successful (from register.php redirection)
if (isset($_GET['registration']) && $_GET['registration'] == 'success') {
    $registration_success_message = "Registration successful! You can now log in.";
} else {
    $registration_success_message = "";
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Initialize variables
    $email = $password_hash_input = "";
    $email_err = $password_err = $login_err = "";

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate hashed password
    if (empty(trim($_POST["hashedPassword"]))) {
        $password_err = "Password hashing failed or no password provided.";
    } else {
        $password_hash_input = trim($_POST["hashedPassword"]);
    }

    // Proceed only if there are no validation errors
    if (empty($email_err) && empty($password_err)) {

        // Prepare SQL statement
        $sql = "SELECT id, first_name, email, password_hash FROM users WHERE email = ?";

        if ($stmt = $mysqli->prepare($sql)) {

            // Bind parameters
            $stmt->bind_param("s", $param_email);
            $param_email = $email;

            // Execute the statement
            if ($stmt->execute()) {

                // Store result
                $stmt->store_result();

                // Check if email exists
                if ($stmt->num_rows === 1) {
                    // Bind result variables
                    $stmt->bind_result($id, $first_name, $email, $hashed_password_from_db);
                    if ($stmt->fetch()) {

                        // Compare SHA-256 hashes
                        if ($password_hash_input === $hashed_password_from_db) {
                            // Success: start session and redirect
                            session_regenerate_id(true);
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $first_name;

                            // ✅ Redirect to home page (adjust the path as needed)
                            header("Location: ../store/store.php");
                            exit();

                        } else {
                            $login_err = "Invalid email or password.";
                        }
                    }
                } else {
                    $login_err = "Invalid email or password.";
                }

            } else {
                $login_err = "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close DB connection
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        async function hashPassword(event) {
            event.preventDefault(); // Prevent default form submission

            let password = document.getElementById("password").value;
            let encoder = new TextEncoder();
            let data = encoder.encode(password);

            // Hash password using SHA-256 (MUST MATCH HASHING METHOD USED DURING REGISTRATION)
            let hashBuffer = await crypto.subtle.digest("SHA-256", data);
            let hashArray = Array.from(new Uint8Array(hashBuffer));
            let hashHex = hashArray.map(b => b.toString(16).padStart(2, "0")).join("");

            // Set hashed password in hidden input
            document.getElementById("hashedPassword").value = hashHex;

            // Submit form
            document.getElementById("loginForm").submit();
        }
    </script>
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .form-control {
            border-radius: 8px;
        }

        .form-control:focus {
            box-shadow: 0px 0px 8px rgba(33, 150, 243, 0.5);
            border-color: #2196f3;
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
    </style>
</head>

<body>
    <div class="login-container">
        <h3 class="text-center">Welcome Back!</h3>

        <?php
        // Display registration success message
        if (!empty($registration_success_message)) {
            echo '<div class="alert alert-success">' . $registration_success_message . '</div>';
        }

        // Display login errors
        if (!empty($email_err)) {
            echo '<div class="alert alert-danger">' . $email_err . '</div>';
        }
        if (!empty($password_err)) {
            echo '<div class="alert alert-danger">' . $password_err . '</div>';
        }
        if (!empty($login_err)) {
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }
        ?>

        <form id="loginForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" onsubmit="hashPassword(event)">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required value="<?php echo htmlspecialchars($email); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" id="password" class="form-control" placeholder="Enter your password" required>
            </div>

            <input type="hidden" id="hashedPassword" name="hashedPassword">

            <button type="submit" class="btn btn-custom w-100">Login</button>
        </form>
        <hr>
        <a href="register.php" class="btn btn-secondary w-100 mb-2">Don't have an account? Register</a>
        <a href="../../index.php" class="btn btn-custom d-flex justify-content-center gap-2">Cancel</a>
    </div>
</body>

</html>