<?php
// Connect to the database
$pdo = new PDO('mysql:host=localhost;dbname=glacialblades', 'username', 'password');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fetch page content based on page_id from URL
if (isset($_GET['page_id'])) {
    $page_id = $_GET['page_id'];
    $stmt = $pdo->prepare("SELECT title, content FROM pages WHERE page_id = ?");
    $stmt->execute([$page_id]);
    $page = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch comments for the current page in reverse chronological order
    $stmt = $pdo->prepare("SELECT user_id, content, submitted_at FROM comments WHERE page_id = ? ORDER BY submitted_at DESC");
    $stmt->execute([$page_id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Redirect if page_id is not provided
    header("Location: view_pages.php");
    exit();
}

// Function to fetch user's name based on user_id
function getUserName($pdo, $user_id)
{
    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user ? $user['username'] : "Anonymous";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page['title']; ?></title>
</head>
<body>
    <h1><?php echo $page['title']; ?></h1>
    <p><?php echo $page['content']; ?></p>

    <!-- Display Comments -->
    <h2>Comments</h2>
    <?php if (!empty($comments)) : ?>
        <ul>
            <?php foreach ($comments as $comment) : ?>
                <li>
                    <strong><?php echo getUserName($pdo, $comment['user_id']); ?>:</strong>
                    <?php echo $comment['content']; ?> (<?php echo $comment['submitted_at']; ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>No comments yet.</p>
    <?php endif; ?>

    <!-- Comment Form -->
    <form action="submit_comment.php" method="post">
        <input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
        <label for="comment_content">Your Comment:</label><br>
        <textarea id="comment_content" name="content" rows="4" cols="50"></textarea><br>
        <?php if (!isset($_SESSION['user_id'])) : ?>
            <label for="name">Your Name:</label><br>
            <input type="text" id="name" name="name"><br>
        <?php endif; ?>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
