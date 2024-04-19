<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Page</title>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="about.html">About Us</a></li>
                <!-- All users -->
                <li><a href="teams.html">Teams</a></li>
                <li><a href="players.html">Players</a></li>
                <li><a href="matches.html">Matches</a></li>
                <li><a href="contact.html">Contact Us</a></li>
                <li><a href="comments.html">Comments</a></li>
                
                <?php 
                if (isset($_SESSION['user_id'])) {
                    echo '<li><a href="create_page.php">Create New Page</a></li>';
                    echo '<li><a href="view_pages.php">View Pages</a></li>';
                    echo '<li><a href="edit_page.php">Edit Page</a></li>';
                    echo '<li><a href="delete_page.php">Delete Page</a></li>';
                    echo '<li><a href="logout.php">Logout</a></li>';
                } else {
                    echo '<li><a href="login.php">Login</a></li>';
                    echo '<li><a href="register.php">Register</a></li>';
                }
                ?>
            </ul>
        </nav>
    </header>

    <h1>Delete Page</h1>
    
</body>
</html>
