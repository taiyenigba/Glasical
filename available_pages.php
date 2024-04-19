<?php
session_start(); // Start the session

try {
    // Connect to the database
    $pdo = new PDO('mysql:host=localhost;dbname=glacialblades', 'username', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute the SQL query to fetch pages
    $stmt = $pdo->prepare("SELECT page_id, title FROM pages");
    $stmt->execute();

    // Fetch all pages as associative array
    $pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // If an exception occurs, display the error message
    echo "Connection failed: " . $e->getMessage();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Pages</title>
</head>
<body>
    <h1>All Pages</h1>
    
    <ul>
        <?php foreach ($pages as $page): ?>
            <li><a href="view_pages.php?id=<?php echo $page['page_id']; ?>"><?php echo $page['title']; ?></a></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>