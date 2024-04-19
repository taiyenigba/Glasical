<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection file
require_once 'config.php';

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $page_id = $_POST['page_id'];
    $category_id = $_POST['category_id'];

    try {
        // Prepare SQL statement to insert data into page_categories table
        $stmt = $pdo->prepare("INSERT INTO page_categories (page_id, category_id) VALUES (:page_id, :category_id)");
        $stmt->bindParam(':page_id', $page_id);
        $stmt->bindParam(':category_id', $category_id);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to a success page or back to the form page
            header("Location: assign_category_success.php");
            exit();
        } else {
            // Handle the case where execution fails
            echo "Error: Unable to assign category to page.";
        }
    } catch(PDOException $e) {
        // Handle database errors
        echo "Error: " . $e->getMessage();
    }
} else {
    // If the form wasn't submitted via POST, redirect to the form page
    header("Location: assign_category.php");
    exit();
}
?>
