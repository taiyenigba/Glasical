<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['page_id']) && isset($_POST['content'])) {
        // If allowing non-logged in users to comment, also capture their name
        $name = isset($_SESSION['user_id']) ? null : $_POST['name']; // Capture name if not logged in
        $page_id = $_POST['page_id'];
        $content = $_POST['content'];

        if (empty($content)) {
            echo "Comment content cannot be empty.";
            exit();
        }

        try {
            $pdo = new PDO('mysql:host=localhost;dbname=glacialblades', 'username', 'password');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare the SQL statement
            $stmt = $pdo->prepare("INSERT INTO comments (page_id, user_id, name, content, submitted_at) VALUES (?, ?, ?, ?, NOW())");

            // Bind parameters and execute the statement
            if(isset($_SESSION['user_id'])){
                // If logged in, insert user_id and set name to null
                $stmt->execute([$page_id, $_SESSION['user_id'], null, $content]);
            } else {
                // If not logged in, insert name and set user_id to null
                $stmt->execute([$page_id, null, $name, $content]);
            }

            // Redirect back to the page where the comment was submitted
            header("Location: view_page_content.php?page_id=$page_id");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Invalid form submission.";
    }
} else {
    echo "Invalid request method.";
}
?>
