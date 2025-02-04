<html>
<head>
<title>MyBlog</title>
<link rel="stylesheet" href="styles/styles.css">
</head>
</body>

<?php include 'includes/db.php'; ?>
<?php include 'includes/header.php'; ?>


    <?php
    $post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $stmt = $pdo->prepare("SELECT * FROM blog WHERE id = :id");
    $stmt->execute(['id' => $post_id]);
    $post = $stmt->fetch();
    if ($post) {
        echo "<h1>{$post['title']}</h1>";
        echo "<p>{$post['text']}</p>";
        echo "<small>Created at: {$post['created_at']}</small>";
    } else {
        echo "<p>Post not found.</p>";
    }
    ?>
    <br><br>
    <a href="index.php" class="button">Back to Home</a>

</body>
</html>
