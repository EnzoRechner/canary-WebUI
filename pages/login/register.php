<?php
// Include the database configuration file
require_once '../../config/config.php'; // Adjust path if your config.php is elsewhere

$first_name = $last_name = $email = $password = $password_hash = "";
$first_name_err = $last_name_err = $email_err = $password_err = "";
$registration_success = false;
$error_message = ""; // To hold general error messages

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate First Name (assuming "Username" from your HTML is first_name)
    // You might want to add a separate input for last_name in your HTML
    if (empty(trim($_POST["username"]))) { // Using "username" as first_name for now based on your HTML
        $first_name_err = "Please enter your first name.";
    } else {
        $first_name = trim($_POST["username"]);
    }

    // Since your HTML has 'username' and not 'first_name' and 'last_name',
    // I'll assume 'username' maps to 'first_name' and 'last_name' can be set to an empty string or derived.
    // For a proper solution, you should add input fields for first_name and last_name in your HTML.
    $last_name = ""; // Defaulting last_name to empty as there's no input for it

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        // Prepare a select statement to check if email already exists
        $sql = "SELECT id FROM users WHERE email = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            $param_email = trim($_POST["email"]);

            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $email_err = "This email is already registered.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                $error_message = "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }

    // Validate password (hashedPassword from JavaScript)
    if (empty(trim($_POST["hashedPassword"]))) {
        $password_err = "Password hashing failed or no password provided.";
    } else {
        // The password has been SHA-256 hashed on the client-side.
        // PHP's `password_hash()` is generally preferred for stronger hashing with salt,
        // but since your client-side JS is doing SHA-256, we'll store that.
        // **SECURITY NOTE:** For a production environment, it is highly recommended
        // to send the plain text password to the server over HTTPS and use PHP's
        // `password_hash()` function with `PASSWORD_DEFAULT` for robust hashing
        // and salting, rather than client-side hashing. Client-side hashing offers
        // no security against a malicious user who can bypass the JavaScript.
        $password_hash = trim($_POST["hashedPassword"]);
    }

    // Check input errors before inserting into database
    if (empty($first_name_err) && empty($email_err) && empty($password_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO users (first_name, last_name, email, password_hash) VALUES (?, ?, ?, ?)";

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("ssss", $param_first_name, $param_last_name, $param_email, $param_password_hash);

            // Set parameters
            $param_first_name = $first_name;
            $param_last_name = $last_name; // This will be empty based on current HTML
            $param_email = $email;
            $param_password_hash = $password_hash; // This is the SHA-256 hash from JS

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                $registration_success = true;
                // Redirect to login page or a success page
                header("../../index.php");
                exit();
            } else {
                $error_message = "Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
}

// Close connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        async function hashPassword(event) {
            event.preventDefault(); // Prevent default form submission

            let password = document.getElementById("password").value;
            let encoder = new TextEncoder();
            let data = encoder.encode(password);

            // Hash password using SHA-256
            let hashBuffer = await crypto.subtle.digest("SHA-256", data);
            let hashArray = Array.from(new Uint8Array(hashBuffer));
            let hashHex = hashArray.map(b => b.toString(16).padStart(2, "0")).join("");

            // Set hashed password in hidden input
            document.getElementById("hashedPassword").value = hashHex;

            // Submit form
            document.getElementById("registerForm").submit();
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

        .register-container {
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
    <div class="register-container">
        <h3 class="text-center">Create Account</h3>

        <?php
        // Display error messages
        if (!empty($first_name_err)) {
            echo '<div class="alert alert-danger">' . $first_name_err . '</div>';
        }
        if (!empty($email_err)) {
            echo '<div class="alert alert-danger">' . $email_err . '</div>';
        }
        if (!empty($password_err)) {
            echo '<div class="alert alert-danger">' . $password_err . '</div>';
        }
        if (!empty($error_message)) {
            echo '<div class="alert alert-danger">' . $error_message . '</div>';
        }
        // No success message here as we are redirecting on success
        ?>

        <form id="registerForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" onsubmit="hashPassword(event)">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Enter your username" required value="<?php echo htmlspecialchars($first_name); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required value="<?php echo htmlspecialchars($email); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" id="password" class="form-control" placeholder="Enter your password" required>
            </div>

            <input type="hidden" id="hashedPassword" name="hashedPassword">

            <button type="submit" class="btn btn-custom w-100">Register</button>
        </form>
        <hr>
        <a href="../../index.php" class="btn btn-custom d-flex justify-content-center gap-2">Cancel</a>
    </div>
    <hr>
</body>

</html>