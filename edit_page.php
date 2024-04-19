<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Establish database connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=glacialblades", "username", "password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Error: Database connection failed: " . $e->getMessage();
    exit();
}

// Check if id is provided in the URL
if (isset($_GET['id'])) {
    // Fetch page data 
    $id = $_GET['id'];
    try {
        $stmt = $pdo->prepare("SELECT p.page_id, p.title, p.content, pc.category_id 
                      FROM pages p 
                      JOIN page_categories pc ON p.page_id = pc.page_id 
                      WHERE p.page_id = :page_id");
        $stmt->bindParam(':page_id', $id);
        $stmt->execute();
        $page = $stmt->fetch(PDO::FETCH_ASSOC);
       
        if (!$page) {
            echo "Page not found";
            exit();
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Define upload directory
$uploadDirectory = "uploads/";

// Fetch categories for the dropdown list
try {
    $stmt = $pdo->query("SELECT * FROM categories");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Page</title>
</head>
<body>
    <h1>Edit Page</h1>
    <h2>Select:</h2>
    <form action="edit_page.php" method="get">
        <select name="id">
            <?php foreach ($pages as $page): ?>
                <option value="<?php echo $page['page_id']; ?>" <?php if(isset($_GET['id']) && $_GET['id'] == $page['page_id']) echo "selected"; ?>><?php echo $page['title']; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Select Page">
    </form>

    <?php if(isset($page)): ?>
        <h2>Edit Page:</h2>
        <form action="update_page.php" method="post">
            <input type="hidden" name="id" value="<?php echo $page['page_id']; ?>">
            <label for="title">Title:</label><br>
            <input type="text" id="title" name="title" value="<?php echo $page['title']; ?>"><br>
            <label for="content">Content:</label><br>
            <textarea id="content" name="content"><?php echo $page['content']; ?></textarea><br>

            <!-- Dropdown list of categories -->
            <label for="category">Select Category:</label><br>
            <select id="category" name="category_id">
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['category_id']; ?>" <?php if(isset($page['category_id']) && $category['category_id'] == $page['category_id']) echo "selected"; ?>><?php echo $category['category_name']; ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <input type="submit" value="Edit">
        </form>

        <h2>Upload Image</h2>
        <form action="update_page.php" method="post" enctype="multipart/form-data">
            <label for="image">Select Image:</label><br>
            <input type="file" id="image" name="image" accept="image/*" required><br><br>
            <input type="submit" value="Upload Image">
        </form>
    <?php endif; ?>
</body>
</html>
