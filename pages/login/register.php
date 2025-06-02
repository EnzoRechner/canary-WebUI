<?php
require_once '../../config/config.php';

$first_name = $last_name = $username = $email = $password_hash = "";
$first_name_err = $last_name_err = $username_err = $email_err = $password_err = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate First Name
    if (empty(trim($_POST["first_name"]))) {
        $first_name_err = "Please enter your first name.";
    } else {
        $first_name = trim($_POST["first_name"]);
    }

    // Validate Last Name
    if (empty(trim($_POST["last_name"]))) {
        $last_name_err = "Please enter your last name.";
    } else {
        $last_name = trim($_POST["last_name"]);
    }

    // Validate Username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please choose a username.";
    } else {
        $sql = "SELECT id FROM users WHERE username = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $param_username);
            $param_username = trim($_POST["username"]);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                $error_message = "Something went wrong. Please try again.";
            }
            $stmt->close();
        }
    }

    // Validate Email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
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
                $error_message = "Something went wrong. Please try again.";
            }
            $stmt->close();
        }
    }

    // Validate Password
    if (empty(trim($_POST["hashedPassword"]))) {
        $password_err = "Password hashing failed or no password provided.";
    } else {
        $password_hash = trim($_POST["hashedPassword"]);
    }

    // Final insert
    if (empty($first_name_err) && empty($last_name_err) && empty($username_err) && empty($email_err) && empty($password_err)) {
        $sql = "INSERT INTO users (first_name, last_name, username, email, password_hash) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("sssss", $param_first_name, $param_last_name, $param_username, $param_email, $param_password_hash);
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_username = $username;
            $param_email = $email;
            $param_password_hash = $password_hash;

            if ($stmt->execute()) {
                header("Location: ../../index.php");
                exit();
            } else {
                $error_message = "Something went wrong during registration.";
            }
            $stmt->close();
        }
    }

    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        async function hashPassword(event) {
            event.preventDefault();
            const password = document.getElementById("password").value;
            const encoder = new TextEncoder();
            const data = encoder.encode(password);
            const hashBuffer = await crypto.subtle.digest("SHA-256", data);
            const hashArray = Array.from(new Uint8Array(hashBuffer));
            const hashHex = hashArray.map(b => b.toString(16).padStart(2, "0")).join("");
            document.getElementById("hashedPassword").value = hashHex;
            document.getElementById("registerForm").submit();
        }
    </script>
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
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
        }

        .btn-custom:hover {
            background-color: #000000;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <h3 class="text-center">Create Account</h3>

        <?php
        if (!empty($first_name_err)) echo '<div class="alert alert-danger">' . $first_name_err . '</div>';
        if (!empty($last_name_err)) echo '<div class="alert alert-danger">' . $last_name_err . '</div>';
        if (!empty($username_err)) echo '<div class="alert alert-danger">' . $username_err . '</div>';
        if (!empty($email_err)) echo '<div class="alert alert-danger">' . $email_err . '</div>';
        if (!empty($password_err)) echo '<div class="alert alert-danger">' . $password_err . '</div>';
        if (!empty($error_message)) echo '<div class="alert alert-danger">' . $error_message . '</div>';
        ?>

        <form id="registerForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="hashPassword(event)">
            <div class="mb-3">
                <label class="form-label">First Name</label>
                <input type="text" name="first_name" class="form-control" required value="<?php echo htmlspecialchars($first_name); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" name="last_name" class="form-control" required value="<?php echo htmlspecialchars($last_name); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required value="<?php echo htmlspecialchars($username); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($email); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" id="password" class="form-control" required>
            </div>

            <input type="hidden" id="hashedPassword" name="hashedPassword">

            <button type="submit" class="btn btn-custom w-100">Register</button>
        </form>
        <hr>
        <a href="../../index.php" class="btn btn-custom d-flex justify-content-center">Cancel</a>
    </div>
</body>

</html>
