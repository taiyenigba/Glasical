<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the page ID is provided in the POST data
    if (isset($_POST['id'])) {
        // Sanitize the page ID
        $page_id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

        // Include the database connection file
        require_once 'config.php';

        try {
            // Prepare a delete statement
            $stmt = $pdo->prepare("DELETE FROM pages WHERE page_id = :page_id");

            // Bind parameters
            $stmt->bindParam(':page_id', $page_id, PDO::PARAM_INT);

            // Attempt to execute the statement
            if ($stmt->execute()) {
                // Redirect to the delete page with a success message
                header("Location: delete_page.php?success=1");
                exit();
            } else {
                // Redirect to the delete page with an error message
                header("Location: delete_page.php?error=1");
                exit();
            }
        } catch(PDOException $e) {
            // Redirect to the delete page with an error message
            header("Location: delete_page.php?error=1");
            exit();
        }
    } else {
        // Redirect to the delete page with an error message
        header("Location: delete_page.php?error=1");
        exit();
    }
} else {
    // Redirect to the delete page if accessed without form submission
    header("Location: delete_page.php");
    exit();
}
?>
