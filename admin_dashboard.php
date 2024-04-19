<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection file
require_once 'config.php';

// Establish a database connection
try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle database connection error
    echo "Connection failed: " . $e->getMessage();
    die();
}

// Fetch all comments from the database
$stmt = $pdo->query("SELECT * FROM comments");
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - GlacialBlades</title>
</head>
<body>

<header>
    <h1>Admin Dashboard</h1>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <!-- Add other navigation links as needed -->
        </ul>
    </nav>
</header>

<main>
    <h2>Moderation</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Page ID</th>
                <th>User ID</th>
                <th>Name</th>
                <th>Content</th>
                <th>Submitted At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($comments as $comment): ?>
                <tr>
                    <td><?php echo $comment['comment_id']; ?></td>
                    <td><?php echo $comment['page_id']; ?></td>
                    <td><?php echo $comment['user_id']; ?></td>
                    <td><?php echo $comment['name']; ?></td>
                    <td><?php echo $comment['content']; ?></td>
                    <td><?php echo $comment['submitted_at']; ?></td>
                    <td>
                        <form action="moderate_comment.php" method="POST">
                            <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>">
                            <select name="action">
                                <option value="delete">Delete</option>
                                <option value="hide">Hide</option>
                                <!-- Add more moderation actions if needed -->
                            </select>
                            <button type="submit">Submit</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

</body>
</html>
