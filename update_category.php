<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection file
require_once 'config.php';

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
    <title>Update Category</title>
</head>
<body>
    <h1>Update Category</h1>
    <form action="update_category_handler.php" method="post">
        <label for="category-select">Select Category:</label>
        <select id="category-select" name="category_id">
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="new-name">New Category Name:</label>
        <input type="text" id="new-name" name="new_name" required>
        <br>
        <input type="submit" value="Update Category">
    </form>
</body>
</html>
