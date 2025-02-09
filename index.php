<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<html>
<head>
<title>MyBlog</title>
<link rel="stylesheet" href="styles/styles.css">
</head>
</body>
<?php include 'includes/db.php'; ?>
<?php include 'includes/header.php'; ?>

    <h1>All Blog Posts</h1>
    <h2>View Post by ID</h2>
    <form method="get" action="post.php">
        <label for="post_id">Enter Post ID:</label>
        <input type="number" id="post_id" name="id" required>
        <button type="submit">View Post</button>
    </form>

    <ul>
        <?php
        $stmt = $pdo->prepare("SELECT blog.*, users.username FROM blog JOIN users ON blog.id=users.user_id+6 ORDER BY id asc;");
        $stmt->execute();
        $posts = $stmt->fetchAll();
        foreach ($posts as $post) {
            echo "<li><a href='post.php?id={$post['id']}'>{$post['id']}. {$post['title']}</a></li><li>Author: {$post['username']}</li><br>";
        }
        ?>
    </ul>


</body>
</html>

