<?php
session_start();

// Include the database connection file
require_once 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect unauthorized users to a login page or display an error message
    header("Location: login.php");
    exit();
}

// Check if the category ID is provided in the URL
if (!isset($_GET['category_id'])) {
    // Redirect back to the categories page or display an error message
    header("Location: categories.php");
    exit();
}

// Fetch the category ID from the URL
$category_id = $_GET['category_id'];

// Fetch the category details from the database based on the provided category ID
try {
    $stmt = $pdo->prepare("SELECT category_name FROM categories WHERE category_id = :category_id");
    $stmt->bindParam(':category_id', $category_id);
    $stmt->execute();
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the category exists
    if (!$category) {
        // Redirect back to the categories page or display an error message
        header("Location: categories.php");
        exit();
    }

    // Extract the category name
    $category_name = $category['category_name'];
} catch (PDOException $e) {
    // Handle database errors
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
    <form action="update_category.php" method="post">
        <!-- Pass the category ID as a hidden input field -->
        <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
        <label for="category_name">Category Name:</label>
        <input type="text" id="category_name" name="category_name" value="<?php echo $category_name; ?>" required>
        <button type="submit">Update Category</button>
    </form>
</body>
</html>
