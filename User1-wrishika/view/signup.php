<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup - RetailFlow</title>
    <link rel="stylesheet" href="signup.css">
    <script src="../Controller/JS/checkEmail.js"></script>
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
        echo '<p class="error-msg">' . $_SESSION["error"] . '</p>';
        unset($_SESSION["error"]);
    }
    ?>
</section>

</body>
</html>
