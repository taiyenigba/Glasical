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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $category_id = $_POST['category_id'];
    $new_name = $_POST['new_name'];

    // Validate data (you might want to add more validation)
    if (empty($new_name)) {
        // Handle empty new name (you might want to display an error message)
        header("Location: update_category.php");
        exit();
    }

    try {
        // Prepare and execute the SQL UPDATE query to update the category name
        $stmt = $pdo->prepare("UPDATE categories SET category_name = :new_name WHERE category_id = :category_id");
        $stmt->bindParam(':new_name', $new_name);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->execute();

        // Redirect to a success page or display a success message
        header("Location: categories.php");
        exit();
    } catch (PDOException $e) {
        // Handle database errors
        echo "Error: " . $e->getMessage();
    }
} else {
    // If the form is not submitted via POST method, redirect to the categories page
    header("Location: categories.php");
    exit();
}
?>
