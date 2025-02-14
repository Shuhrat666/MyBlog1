<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require '../includes/db.php';
require '../Controller/postController.php';

$title = isset($_GET['title']) ? $_GET['title'] : null;
$username = isset($_GET['username']) ? $_GET['username'] : null;
$created_at = isset($_GET['created_at']) ? $_GET['created_at'] : null;
$updated_at = isset($_GET['updated_at']) ? $_GET['updated_at'] : null;
$views = isset($_GET['views']) ? $_GET['views'] : null;

$filters = [];
$params = [];
if ($title) {
    $filters[] = 'blog.title LIKE :title';
    $params['title'] = '%'.$title.'%';
}
if ($username) {
    $filters[] = 'users.username = :username';
    $params['username'] = $username;
}
if ($created_at) {
    $filters[] = 'DATE(blog.created_at) = :created_at';
    $params['created_at'] = $created_at;
}
if ($updated_at) {
    $filters[] = 'DATE(blog.updated_at) = :updated_at';
    $params['updated_at'] = $updated_at;
}
if ($views) {
    $filters[] = 'blog.views >= :views';
    $params['views'] = $views;
}

$filter_query = '';
$filter_query = ' WHERE ' . implode(' AND ', $filters);

$posts = searchPosts($pdo, $filter_query, $params);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Filtered Blog Posts</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <h1>Filtered Blog Posts</h1>

    <form method="get" action="filtered_posts.php">
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

    <ul>
        <?php
        if (count($posts) > 0) {
            foreach ($posts as $post) {
                echo "<form><li><a href='post.php?id={$post['id']}'><b>{$post['id']}. Title:</b> {$post['title']}</a></li>
                      <li>Author: {$post['username']}</li>
                      <li>Created At: {$post['created_at']}</li>
                      <li>Updated At: {$post['updated_at']}</li>
                      <li>Views: {$post['views']}</li><br></form>";
            }
        } else {
            echo "<li>No posts found with the specified filters.</li>";
        }
        ?>
    </ul>
    <label for="back"><a href="/index.php" class="button">Back to Home</a></label>
</body>
</html>
