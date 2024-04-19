<?php
// Enable error reporting to display any errors or warnings
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Define the file_is_an_image function
function file_is_an_image($file) {
    // Get the MIME type of the file
    $mime_type = mime_content_type($file);

    // Check if the MIME type starts with 'image/'
    return substr($mime_type, 0, 6) === 'image/';
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['selected_category_id']; // Retrieve the category ID from the hidden input field

    // Validate input data (you can add more validation if needed)
    if (empty($title) || empty($content) || empty($category_id)) {
        $error = "Title, content, and category are required fields.";
    } else {
        // Check if a file was uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            // Validate the uploaded file to ensure it's an image
            $file = $_FILES['image'];
            if (file_is_an_image($file['tmp_name'])) {
                // Move the uploaded image file to a designated folder on your server
                $uploadsDirectory = 'uploads/';
                $uploadedFilePath = $uploadsDirectory . basename($file['name']);
                if (move_uploaded_file($file['tmp_name'], $uploadedFilePath)) {
                    try {
                        // Establish database connection
                        $pdo = new PDO('mysql:host=localhost;dbname=glacialblades', 'username', 'password');
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // SQL statement to insert new page
                        $stmt = $pdo->prepare("INSERT INTO pages (title, content) VALUES (:title, :content)");
                        $stmt->bindParam(':title', $title);
                        $stmt->bindParam(':content', $content);

                        if ($stmt->execute()) {
                            // Retrieve the ID of the last inserted page
                            $lastInsertId = $pdo->lastInsertId();

                            // Insert the association into the page_categories table
                            $stmt = $pdo->prepare("INSERT INTO page_categories (page_id, category_id) VALUES (:page_id, :category_id)");
                            $stmt->bindParam(':page_id', $lastInsertId);
                            $stmt->bindParam(':category_id', $category_id);

                            if ($stmt->execute()) {
                                // Insert information about the uploaded image into the images table
                                $stmt = $pdo->prepare("INSERT INTO images (page_id, file_path) VALUES (:page_id, :file_path)");
                                $stmt->bindParam(':page_id', $lastInsertId);
                                $stmt->bindParam(':file_path', $uploadedFilePath);
                                $stmt->execute();

                                // Redirect to view_pages.php after successful insertion
                                header("Location: view_pages.php");
                                exit();
                            } else {
                                // Display an error message if execution fails
                                $error = "An error occurred while inserting the page category association.";
                            }
                        } else {
                            // Display an error message if execution fails
                            $error = "An error occurred while inserting the page.";
                        }
                    } catch (PDOException $e) {
                        // Handle database connection errors
                        $error = "Database connection error: " . $e->getMessage();
                    }
                } else {
                    // Display an error message if file upload fails
                    $error = "Failed to move uploaded file to destination folder.";
                }
            } else {
                // Display an error message if the uploaded file is not an image
                $error = "Only image files are allowed.";
            }
        } else {
            // Display an error message if no file was uploaded
            $error = "Please select an image file to upload.";
        }
    }
} else {
    // If the form wasn't submitted via POST, redirect to the create page form
    header("Location: create_page.php");
    exit();
}
?>
