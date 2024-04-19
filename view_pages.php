<?php
session_start();

try {
    // Connect to the database
    $pdo = new PDO('mysql:host=localhost;dbname=glacialblades', 'username', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Initialize sorting variables
    $sortField = isset($_GET['sort']) ? $_GET['sort'] : 'title';
    $sortOrder = isset($_GET['order']) ? $_GET['order'] : 'asc';

    // Validate sort field
    $validSortFields = ['title', 'created_at', 'updated_at'];
    if (!in_array($sortField, $validSortFields)) {
        $sortField = 'title';
    }

    // Determine sorting order for next iteration
    $newSortOrder = ($sortOrder == 'asc') ? 'desc' : 'asc';

    // SQL query to fetch pages with sorting
    $stmt = $pdo->prepare("SELECT page_id, title, created_at, updated_at FROM pages ORDER BY $sortField $sortOrder");
    $stmt->execute();
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Pages</title>
</head>
<body>
    <h1>View Pages</h1>
    
    <p>Sort by:
    <a href="?sort=title&order=<?php echo $sortField == 'title' && $sortOrder == 'asc' ? 'desc' : 'asc'; ?>"><?php echo $sortField == 'title' ? ($sortOrder == 'asc' ? 'Title &#9660;' : 'Title &#9650;') : 'Title'; ?></a> |
    <a href="?sort=created_at&order=<?php echo $sortField == 'created_at' && $sortOrder == 'asc' ? 'desc' : 'asc'; ?>"><?php echo $sortField == 'created_at' ? ($sortOrder == 'asc' ? 'Created At &#9660;' : 'Created At &#9650;') : 'Created At'; ?></a> |
    <a href="?sort=updated_at&order=<?php echo $sortField == 'updated_at' && $sortOrder == 'asc' ? 'desc' : 'asc'; ?>"><?php echo $sortField == 'updated_at' ? ($sortOrder == 'asc' ? 'Updated At &#9660;' : 'Updated At &#9650;') : 'Updated At'; ?></a>
    </p>

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Action</th> <!-- Add new column for action -->
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch and display pages
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>".$row['title']."</td>";
                echo "<td>".$row['created_at']."</td>";
                echo "<td>".$row['updated_at']."</td>";
                // Add a link to view page content
                echo "<td><a href='view_page_content.php?page_id=".$row['page_id']."'>View Content</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
