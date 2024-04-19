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
    <title>View Categories</title>
</head>
<body>
    <h1>View Categories</h1>
    <ul>
        <?php foreach ($categories as $category): ?>
            <li><?php echo $category['category_name']; ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
