<?php
session_start();

if (isset($_SESSION['user_id'])) {
    
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check the username and password  in the form submission
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Validate username and password
        
        if ($username === "username" && $password === "password") {
           
            $_SESSION['user_id'] = 1; 
            $_SESSION['username'] = $username;
            //Redirect
            header("Location: index.php");
            exit();
        } else {
            // Display error message
            $error = "Invalid username or password";
        }
    } else {
        // Form fields are missing, display error message
        $error = "Please enter both username and password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GlacialBlades</title>
</head>
<body>

<main>
    <h1>Login</h1>
    <!-- Error message if authentication fails -->
    <?php if (isset($error)) : ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>

    <!-- Login form -->
    <form action="login.php" method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Login">
    </form>
</main>

</body>
</html>

