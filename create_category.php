<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection file
require_once 'config.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $category_name = trim($_POST['category_name']);

    if (empty($category_name)) {
        $error = "Category name is required.";
    } else {
        // Insert the new category into the categories table
        try {
            $stmt = $pdo->prepare("INSERT INTO categories (category_name) VALUES (?)");
            $stmt->execute([$category_name]);
            
            // Display a success message
            $success_message = "Category created successfully!";
        } catch (PDOException $e) {
            // Handle database error
            $error = "Error creating category: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Category</title>
</head>
<body>
    <h1>Create Category</h1>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <?php if (isset($success_message)) echo "<p>$success_message</p>"; ?>
    <form action="create_category.php" method="post">
        <label for="category_name">Category Name:</label>
        <input type="text" id="category_name" name="category_name" required>
        <br>
        <input type="submit" value="Create Category">
    </form>
</body>
</html>
