<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

//  database connection file
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Page</title>
</head>
<body>
    <h1>Delete Page</h1>
    <form action="delete_page_handler.php" method="post">
        <label for="page-select">Select Page:</label>
        <select id="page-select" name="id">
            <?php foreach ($pages as $page): ?>
                <option value="<?php echo $page['page_id']; ?>"><?php echo $page['title']; ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <input type="submit" value="Delete Page">
    </form>
</body>
</html>
