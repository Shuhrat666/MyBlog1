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

$default_records_per_page = 10;
$records_per_page = isset($_GET['records_per_page']) ? (int)$_GET['records_per_page'] : $default_records_per_page;

if (isset($_GET['records_per_page']) && $_GET['records_per_page'] === 'all') {
    $records_per_page = PHP_INT_MAX;
}

$filters = [];
$params = [];
if ($title) {
    $filters[] = 'blog.title LIKE :title';
    $params[':title'] = '%'.$title.'%';
}
if ($username) {
    $filters[] = 'users.username = :username';
    $params[':username'] = $username;
}
if ($created_at) {
    $filters[] = 'DATE(blog.created_at) = :created_at';
    $params[':created_at'] = $created_at;
}
if ($updated_at) {
    $filters[] = 'DATE(blog.updated_at) = :updated_at';
    $params[':updated_at'] = $updated_at;
}
if ($views) {
    $filters[] = 'blog.views >= :views';
    $params[':views'] = $views;
}

$filter_query = '';
if (!empty($filters)) {
    $filter_query = ' WHERE ' . implode(' AND ', $filters);
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM blog JOIN users ON blog.author_id = users.user_id" . $filter_query . " AND blog.status='published'");
$stmt->execute($params);
$total_records = $stmt->fetchColumn();

if ($records_per_page === PHP_INT_MAX) {
    $records_per_page = $total_records; 
} 

$total_pages = $records_per_page === $total_records ? 1 : ceil($total_records / $records_per_page);
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $records_per_page;

$params[':offset'] = $offset; 
$params[':records_per_page'] = $records_per_page;

$posts = searchPosts($pdo, $filter_query, $params);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Filtered Blog Posts</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
<?php require 'header.php';?>
    <h1>Filtered Blog Posts</h1>

    <form method="get" action="filtered_posts.php">
        <label for="title">Title :</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>"><br>
        <label for="username">Username :</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>"><br>
        <label for="created_at">Created Date:</label>
        <input type="date" id="created_at" name="created_at" value="<?php echo htmlspecialchars($created_at); ?>"><br>
        <label for="updated_at">Updated Date:</label>
        <input type="date" id="updated_at" name="updated_at" value="<?php echo htmlspecialchars($updated_at); ?>"><br>
        <label for="views">Minimum Views:</label>
        <input type="number" id="views" name="views" value="<?php echo htmlspecialchars($views); ?>"><br>
        <button type="submit">Search</button>
    </form>

    <form method="get" action="filtered_posts.php">
        <input type="hidden" name="title" value="<?php echo htmlspecialchars($title); ?>">
        <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
        <input type="hidden" name="created_at" value="<?php echo htmlspecialchars($created_at); ?>">
        <input type="hidden" name="updated_at" value="<?php echo htmlspecialchars($updated_at); ?>">
        <input type="hidden" name="views" value="<?php echo htmlspecialchars($views); ?>">
        <label for="records_per_page">Records per page:</label>
        <select id="records_per_page" name="records_per_page">
            <option value="5" <?php echo $records_per_page == 5 ? 'selected' : ''; ?>>5</option>
            <option value="10" <?php echo $records_per_page == 10 ? 'selected' : ''; ?>>10</option>
            <option value="20" <?php echo $records_per_page == 20 ? 'selected' : ''; ?>>20</option>
            <option value="50" <?php echo $records_per_page == 50 ? 'selected' : ''; ?>>50</option>
            <option value="all" <?php echo isset($_GET['records_per_page']) && $_GET['records_per_page'] === 'all' ? 'selected' : ''; ?>>All</option>
        </select>
        <button type="submit">Set</button>
    </form>

    <ul>
        <?php
        if ($total_records == 0) {
            echo "<li>No posts found with the specified filters.</li>";
        } else {
            foreach ($posts as $post) {
                echo "<form><li><a href='post.php?id={$post['id']}'><b>{$post['id']}. Title:</b> {$post['title']}</a></li>
                      <li>Author: {$post['username']}</li>
                      <li>Created At: {$post['created_at']}</li>
                      <li>Updated At: {$post['updated_at']}</li>
                      <li>Views: {$post['views']}</li><br></form>";
            }
        }
        ?>
    </ul>

    <?php
    if ($total_pages > 1) {
        echo '<div>';
        for ($page = 1; $page <= $total_pages; $page++) {
            echo '<a href="filtered_posts.php?page=' . $page . '&records_per_page=' . htmlspecialchars($_GET['records_per_page']) . '">' . $page . '</a> ';
        }
        echo '</div>';
    }
    ?>
    <br><label for="back"><a href="/index.php" class="button">Back to Home</a></label><br>
    <?php require 'footer.php';?>
</body>
</html>

