<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MyBlog</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>

<?php
include 'includes/db.php';
session_start();
$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$cookie_name = 'viewed_post_' . $post_id;
if (!isset($_COOKIE[$cookie_name])) {
    $stmt = $pdo->prepare("UPDATE blog SET views = views + 1 WHERE id = :id");
    if ($stmt->execute(['id' => $post_id])) {
        setcookie($cookie_name, '1', time() + 86400, "/"); 
    }
}

$stmt = $pdo->prepare("SELECT blog.*, users.username, blog.author_id FROM blog JOIN users ON blog.author_id = users.user_id WHERE blog.id = :id");
$stmt->execute(['id' => $post_id]);
$post = $stmt->fetch();
if ($post) {
    echo "<h1>{$post['title']}</h1>";
    echo "<p>{$post['text']}</p>";
    echo "<p>{$post['username']}</p>";
    echo "<small>Created at: {$post['created_at']}</small>";
    echo "<br><small>Updated at: {$post['updated_at']}</small>";
    echo "<br><small>Views: {$post['views']}</small>";
    echo '<h2><b>Comments:</b></h2>';
    if (isset($post['comment']) && $post['comment'] !== null) {
        $comments = explode("\n", $post['comment']);
        foreach ($comments as $comment) {
            echo "<p>" . htmlspecialchars($comment) . "</p>";
        }
    } else {
        echo "<p>No comments yet.</p>";
    }
    if ($_SESSION['user_id'] === $post['author_id']) {
        echo '<h2>Edit Post</h2>';
        echo '<form method="post" action="post.php?id=' . $post_id . '">';
        echo '<label for="title">Title:</label><br>';
        echo '<input type="text" id="title" name="title" value="' . htmlspecialchars($post['title']) . '"><br>';
        echo '<label for="text">Text:</label><br>';
        echo '<textarea id="text" name="text">' . htmlspecialchars($post['text']) . '</textarea><br>';
        echo '<button type="submit" name="updatepost">Update Post</button>';
        echo '</form>';
    }
} else {
    echo "<p>Post not found.</p>";
}
?>

<br>
<form method="post" action="post.php?id=<?php echo $post_id; ?>">
    <label>Enter your comment:</label><br>
    <textarea name="newcomment"></textarea><br>
    <button type="submit" name="postcomment">Post</button>
</form>
<br><br>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['postcomment']) && !empty($_POST['newcomment'])) {
        $newcomment = trim($_POST['newcomment']);
        $stmt = $pdo->prepare("SELECT comment FROM blog WHERE id = :id");
        $stmt->execute(['id' => $post_id]);
        $result = $stmt->fetch();
        $comments = $result ? $result['comment'] : '';
        $comments = $comments . "\n" . $newcomment;
        $stmt = $pdo->prepare("UPDATE blog SET comment = :comment WHERE id = :id");
        $stmt->execute(['id' => $post_id, 'comment' => $comments]);
        header("Location: ?id=$post_id");
        exit;
    }
    if (isset($_POST['updatepost'])) {
        $title = trim($_POST['title']);
        $text = trim($_POST['text']);
        $stmt = $pdo->prepare("UPDATE blog SET title = :title, text = :text, updated_at = NOW() WHERE id = :id AND author_id = :author_id");
        $stmt->execute(['title' => $title, 'text' => $text, 'id' => $post_id, 'author_id' => $_SESSION['user_id']]);
        header("Location: ?id=$post_id");
        exit;
    }
}
?>

<a href="index.php" class="button">Back to Home</a>

</body>
</html>
