<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - RetailFlow</title>

    
    <link rel="stylesheet" href="login.css">
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
