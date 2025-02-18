<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Post</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>

<?php
require '../includes/db.php';
require '../Controller/postController.php';
require 'header.php';
session_start();

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$cookie_name = 'viewed_post_' . $post_id;
if (!isset($_COOKIE[$cookie_name])) {
    if (updatePostViews($pdo, $post_id)) {
        setcookie($cookie_name, '1', time() + 86400, "/"); 
    }
}

$post = getPostById($pdo, $post_id);
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
        echo '<button type="submit" name="update_publish">Update/Publish</button><span>    </span>';
        echo '<button type="submit" name="update_draft">Update/Draft</button><br>';
        echo '<br><button type="submit" name="delete" style="background-color: red;">Delete Post</button>';
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

        updatePostComments($pdo, $post_id, $comments);
        header("Location: ?id=$post_id");
        exit;
    }
    if (isset($_POST['update_publish'])) {
        $title = trim($_POST['title']);
        $text = trim($_POST['text']);
        $status = 'published';

        updatePost($pdo, $title, $text, $status, $post_id, $_SESSION['user_id']);
        header("Location: ?id=$post_id");
        exit;
    }
    if (isset($_POST['update_draft'])) {
        $title = trim($_POST['title']);
        $text = trim($_POST['text']);
        $status = 'drafted';

        updatePost($pdo, $title, $text, $status, $post_id, $_SESSION['user_id']);
        header("Location: ?id=$post_id");
        exit;
    }
    if (isset($_POST['delete'])) {
        deletePost($pdo, $post_id);
        header("Location: /index.php");
        exit;
    }
}
?>

<label for="back"><a href="/index.php" class="button">Back to Home</a></label><br>
<?php require 'footer.php';?>
</body>
</html>
