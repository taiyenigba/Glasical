<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection file
require_once 'config.php';

// Fetch all pages from the database
try {
    $stmt = $pdo->query("SELECT * FROM pages");
    $pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!$pages) {
        echo "No pages found";
        exit();
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Fetch all categories from the database
try {
    $stmt = $pdo->query("SELECT * FROM categories");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!$categories) {
        echo "No categories found";
        exit();
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Category</title>
</head>
<body>
    <h1>Assign Category</h1>
    <form action="assign_category_handler.php" method="post">
        <label for="page-select">Select Page:</label>
        <select id="page-select" name="page_id">
            <?php foreach ($pages as $page): ?>
                <option value="<?php echo $page['page_id']; ?>"><?php echo $page['title']; ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="category-select">Select Category:</label>
        <select id="category-select" name="category_id">
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <input type="submit" value="Assign Category">
    </form>
</body>
</html>
