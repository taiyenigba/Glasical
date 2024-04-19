<?php
session_start();

// Fetch page titles from the database
try {
    $pdo = new PDO('mysql:host=localhost;dbname=glacialblades', 'username', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT page_id, title FROM pages");
    $stmt->execute();
    $pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Players </title>
  <h1>Search</h1>
    <form action="search.php" method="GET">
      <input type="text" name="query" placeholder="Search Here">
      <button type="submit">Search</button>
    </form>
</head>
<body>

    <header>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="available_pages.php">Available Pages</a></li> <!-- Link to available pages -->
                    <!-- Remaining menu items based on user authentication -->
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
                        <li><a href="logout.php">Logout</a></li>
                    <?php } else { ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    <?php } ?>
                </ul>
            </nav>
      </header>

<main>
    
  <h1>Players - GlacialBlades</h1>
  <p></p>
</main>

</body>
</html>
