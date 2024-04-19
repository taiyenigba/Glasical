<?php
session_start(); // Start the session

try {
    // Connect to the database
    $pdo = new PDO('mysql:host=localhost;dbname=glacialblades', 'username', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute the SQL query to fetch pages
    $stmt = $pdo->prepare("SELECT page_id, title FROM pages");
    $stmt->execute();

    // Fetch all pages as associative array
    $pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // If an exception occurs, display the error message
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GlacialBlades</title>
  <h1>Search</h1>
    <form action="search.php" method="GET">
      <input type="text" name="query" placeholder="Search Here">
      <button type="submit">Search</button>
    </form>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
<nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="category_pages.php?category_id=1">Hockey News</a></li>
            <li><a href="category_pages.php?category_id=2">Company News</a></li>
            <li><a href="category_pages.php?category_id=3">Endoesments</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="available_pages.php">Available Pages</a></li>
            <li><a href="teams.php">Teams</a></li>
            <li><a href="players.php">Players</a></li>
            <li><a href="matches.php">Matches</a></li>
            <li><a href="contact.php">Contact Us</a></li>
            <li><a href="comments.php">Comments</a></li>
            <li><a href="available_pages.php">Available Pages</a></li>
            <?php if(isset($_SESSION['user_id'])) { ?>
                <li><a href="create_page.php">Create New Page</a></li>
            
                <li><a href="view_pages.php">View Pages</a></li>
                <li><a href="edit_page.php?id=1">Edit Page</a></li>
                <li><a href="delete_page.php">Delete Page</a></li>
                <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
                <!-- Add the link to create category form -->
                <li><a href="create_category_form.php">Create Category</a></li>
                <!-- Add the link to update category form -->
                <a href="update_category_form.php?category_id=1">Update Category</a>
            <?php } else { ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php } ?>
        </ul>
    </nav>
</header>

<main>
    <h1>GlacialBlades</h1>
    <p>GlacialBlades is a leading sports entity in Winnipeg dedicated to advancing ice hockey at both amateur and professional levels. The organization primarily aims to nurture a lively ice hockey community by offering extensive assistance to teams, players, and enthusiasts.</p>
</main>

</body>
</html>
