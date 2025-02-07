<form>
<head>
<title>MyBlog</title>
<link rel="stylesheet" href="styles/styles.css">
</head>
</body>

<?php include 'includes/db.php'; ?>
<?php include 'includes/header.php'; ?>


    <?php
    $post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $stmt = $pdo->prepare("UPDATE blog SET views=views+1 WHERE id = :id");
    $stmt->execute(['id' => $post_id]);
    $stmt = $pdo->prepare("SELECT blog.*, users.username FROM blog JOIN users ON blog.id = users.user_id + 6 WHERE blog.id = :id");
    $stmt->execute(['id' => $post_id]);
    $post = $stmt->fetch();
    if ($post) {
        echo "<h1>{$post['title']}</h1>";
        echo "<p>{$post['text']}</p>";
        echo "<p>{$post['username']}</p>";
        echo "<small>Created at: {$post['created_at']}</small>";
        echo "<small>Updated at: {$post['updated_at']}</small>";
        echo "<small>Views: {$post['views']}</small>";
        $comments = explode("\n", $post['comments']);
        foreach ($comments as $comment) {
            echo "<p>" . htmlspecialchars($comment) . "</p>";
        }
    } else {
        echo "<p>Post not found.</p>";
    }
    ?>
    <br>
    <form method="post" action="post.php">
        <label>Enter your comment:</label><br>
        <textarea name="newcomment"></textarea><br>
        <button type="submit" name="postcomment">Post</button>
    </form>
    <br><br>
    
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['postcomment']) && !empty($_POST['newcomment'])) {
            $newcomment = trim($_POST['newcomment']);
            $stmt = $pdo->prepare("SELECT comments FROM blog WHERE id = :id");
            $stmt->execute(['id' => $post_id]);
            $result = $stmt->fetch();
            $comments = $result ? $result['comments'] : '';
            $comments = $comments . "\n" . $newcomment;
            $stmt = $pdo->prepare("UPDATE blog SET comments = :comments WHERE id = :id");
            $stmt->execute(["id"=> $post_id, "comments"=> $comments]);
            header("Location: ?id=$post_id");
            exit;
        }
    }
    ?>

    <a href="index.php" class="button">Back to Home</a>

</body>
</html>
