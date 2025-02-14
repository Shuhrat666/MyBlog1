<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: View/login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MyBlog</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
<?php
    require 'includes/db.php';
    require 'Controller/postController.php';
?>

<h1>All Blog Posts</h1>

<h2>Search posts :</h2>
<form method="get" action="View/filtered_posts.php">
    <label for="title">Title :</label>
    <input type="text" id="title" name="title"><br>
    <label for="username">Username :</label>
    <input type="text" id="username" name="username"><br>
    <label for="created_at">Created Date:</label>
    <input type="date" id="created_at" name="created_at"><br>
    <label for="updated_at">Updated Date:</label>
    <input type="date" id="updated_at" name="updated_at"><br>
    <label for="views">Minimum Views:</label>
    <input type="number" id="views" name="views"><br>
    <button type="submit">Search</button>
</form>

<label for="my_posts"><a href="View/user_own_posts.php" class="button">My Posts</a></label>
<label for="new_post"><a href="View/new_post.php" class="button">Add New Post</a></label>

<ul>
    <?php
    $posts = AllPublishedPosts($pdo);
    foreach ($posts as $post) {
        echo "<form><li><a href='View/post.php?id={$post['id']}'><b class='title'>{$post['id']}. Title:</b> {$post['title']}</a></li><li>Author: {$post['username']}</li><br></form>";
    }
    ?>
</ul>
</body>
</html>

