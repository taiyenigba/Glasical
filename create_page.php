<?php
// Define the file_is_an_image function
function file_is_an_image($file) {
    // Get the MIME type of the file
    $mime_type = mime_content_type($file);

    // Check if the MIME type starts with 'image/'
    return substr($mime_type, 0, 6) === 'image/';
}

// Establish database connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=glacialblades", "username", "password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
    exit(); // Terminate script if unable to connect to the database
}

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// role key 
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

// Fetch categories from the database
$stmt = $pdo->prepare("SELECT DISTINCT c.category_id, c.category_name
                       FROM page_categories pc
                       INNER JOIN categories c ON pc.category_id = c.category_id
                       ORDER BY c.category_name");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Page - GlacialBlades CMS</title>
</head>
<body>

<header>
    <nav>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="about.html">About Us</a></li>
            <!-- All users -->
            <li><a href="teams.html">Teams</a></li>
            <li><a href="players.html">Players</a></li>
            <li><a href="matches.html">Matches</a></li>
            <li><a href="contact.html">Contact Us</a></li>
            <li><a href="comments.html">Comments</a></li>
            
            <?php 
            
            if ($role === 'admin') {
                // Display admin-specific links
                ?>
                <li><a href="create_page.php">Create New Page</a></li>
                <li><a href="view_pages.php">View Pages</a></li>
                <li><a href="edit_page.php">Edit Page</a></li>
                <li><a href="delete_page.php">Delete Page</a></li>
                <?php
            }
            ?>
            
            
            <li><a href="logout.php">Logout</a></li>
            
            <?php 
            // Check if the user is not logged in
            if (!isset($_SESSION['user_id'])) {
              
                ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
                <?php
            }
            ?>
        </ul>
    </nav>
</header>

<main>
    <h1>Create New Page</h1>
    <form action="insert_page.php" method="post" enctype="multipart/form-data">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" required><br><br>
        <label for="content">Content:</label><br>
        <textarea id="content" name="content" rows="4" cols="50" required></textarea><br><br>
        
        <!-- Image upload field -->
        <label for="image">Upload Image:</label><br>
<input type="file" id="image" name="image" accept="image/*"><br><br>
        
        <!-- Other input fields for page title and content -->
        <!-- Dropdown list of categories -->
        <label for="category">Select Category:</label><br>
        <select id="category" onchange="updateSelectedCategory()" name="category_id">
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <input type="hidden" id="selected_category" name="selected_category_id">

        <input type="submit" value="Create">
    </form>
<script>
    // JavaScript function to update the hidden input field with the selected category ID
    function updateSelectedCategory() {
        var selectedCategoryId = document.getElementById("category").value;
        document.getElementById("selected_category").value = selectedCategoryId;
    }
</script>
</main>

</body>
</html>
