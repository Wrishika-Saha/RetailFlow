<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup - RetailFlow</title>
    <script src="../Controller/JS/checkEmail.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        #signup-section {
            background-color: #ffffff;
            padding: 25px;
            width: 400px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #000;
        }

        form label {
            display: block;
            margin-bottom: 5px;
            color: #000;
        }

        form input[type="text"],
        form input[type="password"],
        form input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            border: 1px solid #007BFF;
            color: #fff;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        #erroremail {
            color: red;
            font-size: 12px;
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
        }

        .login-link a {
            color: #007BFF;
            text-decoration: none;
        }
    </style>
</head>
<body>

<section id="signup-section">
    <h2>Create Your Account</h2>

    <form method="post" action="../Controller/signUpValidation.php" enctype="multipart/form-data">
        <label>Full Name:</label>
        <input type="text" name="name" required>

        <label>Email:</label>
        <input type="text" name="email" id="email" onkeyup="findExistingEmail()" required>
        <span id="erroremail"></span>

        <label>Password:</label>
        <input type="password" name="password" required>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" required>

        <label>Profile Picture:</label>
        <input type="file" name="profile">

        <input type="submit" value="Sign Up">

        <div class="login-link">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </form>

    <?php
    if (isset($_SESSION["error"])) {
        echo '<p style="color:red; text-align:center;">' . $_SESSION["error"] . '</p>';
        unset($_SESSION["error"]);
    }
    ?>
</section>

</body>
</html>
