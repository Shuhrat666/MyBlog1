<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Posts</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
<?php 
session_start();
require '../includes/db.php';
require '../Controller/postController.php';
?>

<h1>My Posts</h1>
<h2>View Post by ID</h2>
<form method="get" action="post.php">
    <label for="post_id">Enter Post ID:</label>
    <input type="number" id="post_id" name="id" required>
    <button type="submit">View Post</button>
</form>

<a href="new_post.php" class="button">Add New Post</a>

<ul>
    <?php
    if (isset($_SESSION['user_id'])) {
        $posts = UserPosts($pdo, $_SESSION['user_id']);
        foreach ($posts as $post) {
            echo "<form><li><a href='post.php?id={$post['id']}'>{$post['id']}. {$post['title']}</a></li><li>Author: {$post['username']}</li><li>Status : {$post['status']}</li><br></form>";
        }
    } else {
        echo "No posts found.";
    }
    ?>
</ul>
<label for="back"><a href="/index.php" class="button">Back to Home</a></label>

</body>
</html>
