<?php
// Define the file_is_an_image function
function file_is_an_image($file) {
    // Get the MIME type of the file
    $mime_type = mime_content_type($file);

    // Check if the MIME type starts with 'image/'
    return substr($mime_type, 0, 6) === 'image/';
}
?>
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set
    if (isset($_POST['id']) && isset($_POST['title']) && isset($_POST['content'])) {
        // Sanitize input data
        $id = $_POST['id'];
        $title = htmlspecialchars($_POST['title']);
        $content = htmlspecialchars($_POST['content']);

        // File upload handling
        if ($_FILES['image']['error'] === 0) {

            // Check if the uploaded file is an image
            if (file_is_an_image($_FILES['image']['tmp_name'])) {
                // Define the upload directory and filename
                $upload_dir = 'your_upload_directory_path/'; // Adjust the path accordingly
                $upload_file = $upload_dir . basename($_FILES['image']['name']);

                // Move the uploaded image to the designated folder
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_file)) {
                    // Image upload successful, proceed with updating the page
                    try {
                        // Connect to the database
                        $pdo = new PDO("mysql:host=localhost;dbname=glacialblades", "username", "password");
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Prepare SQL statement to update page data
                        $stmt = $pdo->prepare("UPDATE pages SET title = :title, content = :content, image_path = :image_path WHERE page_id = :id");
                        $stmt->bindParam(':title', $title);
                        $stmt->bindParam(':content', $content);
                        $stmt->bindParam(':image_path', $upload_file);
                        $stmt->bindParam(':id', $id);
                        $stmt->execute();

                        // Redirect back to the edit page with success message
                        header("Location: edit_page.php?id=$id&success=1");
                        exit();
                    } catch(PDOException $e) {
                        // Handle database errors
                        echo "Error: " . $e->getMessage();
                    }
                } else {
                    // Error moving uploaded file
                    echo "Error: Unable to move uploaded file.";
                }
            } else {
                // Uploaded file is not an image
                echo "Error: Uploaded file is not an image.";
            }
        } else {
            // No file uploaded or error occurred
            echo "No file uploaded or an error occurred while uploading the file.";
        }
    } else {
        // Handle missing fields
        echo "All fields are required";
    }
} else {
    // Redirect if accessed directly
    header("Location: edit_page.php");
    exit();
}
?>

