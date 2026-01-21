<?php session_start(); ?>
<?php include('../View/header.php'); ?>

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

    
    <?php
    if (isset($_SESSION["error"]) && !empty($_SESSION["error"])) {
        echo '<div class="error-msg">' . $_SESSION["error"] . '</div>';
        unset($_SESSION["error"]);
    }

    if (isset($_SESSION["success"]) && !empty($_SESSION["success"])) {
        echo '<div class="success-msg">' . $_SESSION["success"] . '</div>';
        unset($_SESSION["success"]);
    }
    ?>

    <form method="post" action="../Controller/signUpValidation.php" enctype="multipart/form-data">
        <label>Full Name:</label>
        <input type="text" name="name">

        <label>Email:</label>
        <input type="text" name="email" id="email" onkeyup="findExistingEmail()">
        <span id="erroremail"></span>

        <label>Password:</label>
        <input type="password" name="password">

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password">

        <label>Profile Picture:</label>
        <input type="file" name="profile">

        <input type="submit" value="Sign Up">

        <div class="login-link">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </form>
</section>

</body>
</html>

<?php include('../View/footer.php'); ?>
