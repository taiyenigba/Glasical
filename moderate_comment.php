<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection file
require_once 'config.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the required fields are set
    if (isset($_POST['comment_id'], $_POST['action'])) {
        $comment_id = $_POST['comment_id'];
        $action = $_POST['action'];

        // Perform the moderation action based on the submitted action
        switch ($action) {
            case 'delete':
                // Delete the comment from the database
                $stmt = $pdo->prepare("DELETE FROM comments WHERE comment_id = ?");
                $stmt->execute([$comment_id]);
                break;
            case 'hide':
                // Update the comment status to hidden
                $stmt = $pdo->prepare("UPDATE comments SET hidden = 1 WHERE comment_id = ?");
                $stmt->execute([$comment_id]);
                break;
            default:
                // Invalid action, redirect back to the admin dashboard
                header("Location: admin_dashboard.php");
                exit();
        }

        // Redirect back to the admin dashboard after the moderation action
        header("Location: admin_dashboard.php");
        exit();
    }
}

// If the form was not submitted or the required fields are not set, redirect back to the admin dashboard
header("Location: admin_dashboard.php");
exit();
?>
