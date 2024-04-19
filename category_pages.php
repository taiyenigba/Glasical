<?php
session_start(); // Start the session

if(isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];
    try {
        // Connect to the database
        $pdo = new PDO('mysql:host=localhost;dbname=glacialblades', 'username', 'password');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch the category name based on the provided category_id
        $stmt = $pdo->prepare("SELECT category_name FROM categories WHERE category_id = ?");
        $stmt->execute([$category_id]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        // Prepare and execute the SQL query to fetch pages for the selected category
        $stmt = $pdo->prepare("
        SELECT p.page_id, p.title 
        FROM pages p 
        INNER JOIN page_categories pc ON p.page_id = pc.page_id 
        WHERE pc.category_id = ?
    ");
    $stmt->execute([$category_id]);

        // Fetch all pages for the selected category as associative array
        $pages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if pages were fetched successfully
        if (!$pages) {
            throw new Exception("No pages found for the selected category.");
        }
    } catch (PDOException $e) {
        // If an exception occurs while executing the query, display the error message
        echo "Connection failed: " . $e->getMessage();
    } catch (Exception $e) {
        // If no pages were found, display the error message
        echo $e->getMessage();
    }
} else {
    // Redirect back to categories.php if category_id is not set
    header("Location: categories.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $category['category_name']; ?></title>
</head>
<body>

<header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <!-- Remaining menu items based on user authentication -->
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
  <h1><?php echo $category['category_name']; ?></h1>
  <ul>
    <?php foreach ($pages as $page): ?>
      <li><a href="view_pages.php?page_id=<?php echo $page['page_id']; ?>"><?php echo $page['title']; ?></a></li>
    <?php endforeach; ?>
  </ul>
</main>

</body>
</html>
