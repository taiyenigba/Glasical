<?php
session_start(); // Start the session

try {
    // Connect to the database
    $pdo = new PDO('mysql:host=localhost;dbname=glacialblades', 'username', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute the SQL query to fetch categories
    $stmt = $pdo->query("SELECT * FROM categories");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
  <title>Categories</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <!-- Remaining menu items based on user authentication -->
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
  <!-- Display the list of categories -->
  <h1>Categories</h1>
  <ul>
    <?php foreach ($categories as $category): ?>
        <li><a href="category_pages.php?category_id=<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></a></li>
    <?php endforeach; ?>
  </ul>
</main>

</body>
</html>
