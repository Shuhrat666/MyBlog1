<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['newpost']) && !empty($_POST['title']) && !empty($_POST['text'])) {
        $title = trim($_POST['title']);
        $text = trim($_POST['text']);
        $author_id = $_SESSION['user_id'];

        $stmt = $pdo->prepare("INSERT INTO blog (title, text, author_id, created_at, updated_at, views, comment) VALUES (:title, :text, :author_id, NOW(), NULL, 0, '')");
        $stmt->execute(['title' => $title, 'text' => $text, 'author_id' => $author_id]);

        header('Location: index.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Post</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <h2>Add New Post</h2>
    <form method="post" action="new_post.php">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" required><br>
        <label for="text">Text:</label><br>
        <textarea id="text" name="text" required></textarea><br>
        <button type="submit" name="newpost">Add Post</button>
    </form>
    <a href="index.php" class="button">Back to Home</a>
</body>
</html>
