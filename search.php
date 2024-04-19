<?php
session_start(); // Start session

// Set the number of results per page
$results_per_page = 2;

// Check if the page parameter is set, otherwise default to page 1
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculate the LIMIT and OFFSET values for the SQL query
$offset = ($current_page - 1) * $results_per_page;

// Check if the form is submitted with a search query
if(isset($_GET['query'])) {
    // Get the search query from the form
    $search_query = htmlspecialchars($_GET['query']); 
    // Get the selected category ID from the form
    $category_id = isset($_GET['category']) ? $_GET['category'] : null;

    try {
        // Connect to the database
        $pdo = new PDO('mysql:host=localhost;dbname=glacialblades', 'username', 'password');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the SQL query to search for pages with LIMIT and OFFSET
        $sql = "SELECT p.page_id, p.title, c.category_name
                FROM pages p
                LEFT JOIN page_categories pc ON p.page_id = pc.page_id
                LEFT JOIN categories c ON pc.category_id = c.category_id
                WHERE p.title LIKE :search_query";
                
        // If a category is selected, add a condition to filter by category ID
        if ($category_id) {
            $sql .= " AND pc.category_id = :category_id";
        }

        $sql .= " LIMIT :limit OFFSET :offset";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':search_query', "%$search_query%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', $results_per_page, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        // If a category is selected, bind the category ID parameter
        if ($category_id) {
            $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
        }

        $stmt->execute();

        // Fetch the search results as an associative array
        $search_results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Count the total number of results for pagination
        $total_results = $pdo->query("SELECT COUNT(*) FROM pages WHERE title LIKE '%$search_query%'")->fetchColumn();
    } catch (PDOException $e) {
        // If an exception occurs, display the error message
        echo "Connection failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search | GlacialBlades</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <!-- Include additional navigation links here if needed -->
            <li><a href="teams.php">Teams</a></li>
            <li><a href="players.php">Players</a></li>
            <li><a href="matches.php">Matches</a></li>
            <li><a href="contact.php">Contact Us</a></li>
            <li><a href="comments.php">Comments</a></li>
        </ul>
    </nav>
</header>

<main>
  <h1>Search</h1>
  <form action="search.php" method="GET">
    <input type="text" name="query" placeholder="Search Here.">
    <select name="category">
        <option value="">All Categories</option>
        <?php
        // Fetch categories from the database and populate the dropdown menu
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=glacialblades', 'username', 'password');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $categories_stmt = $pdo->query("SELECT * FROM categories");
            while ($category = $categories_stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$category['category_id']}'>{$category['category_name']}</option>";
            }
        } catch (PDOException $e) {
            // If an exception occurs, display the error message
            echo "Connection failed: " . $e->getMessage();
        }
        ?>
    </select>
    <button type="submit">Search</button>
  </form>

 <!-- Display the selected category -->
<?php if ($category_id): ?>
    <?php try {
            $pdo = new PDO('mysql:host=localhost;dbname=glacialblades', 'username', 'password');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $category_stmt = $pdo->prepare("SELECT * FROM categories WHERE category_id = ?");
            $category_stmt->execute([$category_id]);
            $category = $category_stmt->fetch(PDO::FETCH_ASSOC);
            if ($category) {
                echo "<h2>Category: {$category['category_name']}</h2>";
            } else {
                echo "<h2>All Categories</h2>";
            }
        } catch (PDOException $e) {
            // If an exception occurs, display the error message
            echo "Connection failed: " . $e->getMessage();
        }
?>
<?php else: ?>
    <h2>All Categories</h2>
<?php endif; ?>

<!-- Display search results -->
<?php if(isset($search_results) && !empty($search_results)) : ?>
    <ul>
        <?php foreach($search_results as $result) : ?>
            <li><a href="view_page_content.php?id=<?php echo $result['page_id']; ?>"><?php echo $result['title']; ?></a></li>
        <?php endforeach; ?>
    </ul>
    <!-- Pagination  -->
    <?php
    $total_pages = ceil($total_results / $results_per_page);
    if ($total_pages > 1) {
        echo "<div>";
        if ($current_page > 1) {
            echo "<a href='search.php?query=$search_query&category=$category_id&page=1'>First</a>";
            $prev_page = $current_page - 1;
            echo "<a href='search.php?query=$search_query&category=$category_id&page=$prev_page'>Previous</a>";
        }
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $current_page) {
                echo "<span>$i</span>";
            } else {
                echo "<a href='search.php?query=$search_query&category=$category_id&page=$i'>$i</a>";
            }
        }
        if ($current_page < $total_pages) {
            $next_page = $current_page + 1;
            echo "<a href='search.php?query=$search_query&category=$category_id&page=$next_page'>Next</a>";
            echo "</div>";
        }
    }
    ?>
<?php endif; ?>
</main>

</body>
</html>
