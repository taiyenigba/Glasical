<?php
session_start();

// Redirect user to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // New user into the database
  
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=glacialblades", "username", "password");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // Redirect to login page
        header("Location: login.php");
        exit();
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - GlacialBlades</title>
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
      
    </ul>
  </nav>
</header>

<main>
  <h1>Register</h1>

</main>

</body>
</html>
