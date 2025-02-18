<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require '../includes/db.php';
require '../Controller/postController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $text = trim($_POST['text']);
    $author_id = $_SESSION['user_id'];

    if (isset($_POST['publish']) && !empty($title) && !empty($text)) {
        $status = 'published';
        createPost($pdo, $title, $text, $author_id, $status);
        header('Location: /index.php');
        exit();
    } else if (isset($_POST['draft']) && !empty($title) && !empty($text)) {
        $status = 'drafted';
        createPost($pdo, $title, $text, $author_id, $status);
        header('Location: /index.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Post</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
<?php require 'header.php';?>
    <h2>Add New Post</h2>
    <form method="post" action="new_post.php">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" required><br>
        <label for="text">Text:</label><br>
        <textarea id="text" name="text" required></textarea><br>
        <button type="submit" name="publish">Publish</button>
        <button type="submit" name="draft">Draft</button>
    </form>
    <label for="back"><a href="/index.php" class="button">Back to Home</a></label><br>
    <?php require 'footer.php';?>
</body>
</html>
