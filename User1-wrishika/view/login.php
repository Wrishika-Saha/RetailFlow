<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - RetailFlow</title>
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

        #login-section {
            background-color: #ffffff;
            padding: 25px;
            width: 350px;
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
        form input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 15px;
            color: #000;
        }

        input[type="checkbox"] {
            transform: scale(1.1);
            cursor: pointer;
        }

        .login-btn {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            border: 1px solid #007BFF;
            color: #fff;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
        }

        .login-btn:hover {
            background-color: #0056b3;
        }

        .signup-link {
            text-align: center;
            margin-top: 15px;
        }

        .signup-link a {
            color: #007BFF;
            text-decoration: none;
        }

        .error-msg {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <section id="login-section">
        <h2>Login to Your Account</h2>

        <?php
        if (isset($_SESSION["error"]) && !empty($_SESSION["error"])) {
            echo '<div class="error-msg">' . $_SESSION["error"] . '</div>';
            unset($_SESSION["error"]);
        }
        ?>

        <form method="post" action="../Controller/loginValidation.php">
            <label>Email:</label>
            <input type="text" name="email" placeholder="Enter your email" required>

            <label>Password:</label>
            <input type="password" name="password" placeholder="Enter your password" required>

            <div class="remember">
                <input type="checkbox" name="remember">
                <span>Remember Me</span>
            </div>

            <input type="submit" class="login-btn" value="Login">

            <div class="signup-link">
                <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
            </div>
        </form>
    </section>
</body>
</html>
