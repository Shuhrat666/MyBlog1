<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: View/login.php');
    exit();
}

require 'includes/db.php';  
require 'Controller/postController.php';


$default_records_per_page = 10;
$records_per_page = isset($_GET['records_per_page']) ? (int)$_GET['records_per_page'] : $default_records_per_page;


$stmt = $pdo->prepare("SELECT COUNT(*) FROM blog WHERE blog.status='published';");
$stmt->execute();
$total_records = $stmt->fetchColumn();


if (isset($_GET['records_per_page']) && $_GET['records_per_page'] === 'all') {
    $records_per_page = $total_records; 
} 

$total_pages = $records_per_page === $total_records ? 1 : ceil($total_records / $records_per_page);
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $records_per_page;

$stmt = $pdo->prepare("SELECT blog.*, users.username FROM blog JOIN users ON blog.author_id = users.user_id WHERE blog.status='published' ORDER BY blog.id ASC LIMIT :offset, :records_per_page");
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <img src="https://cdn.logojoy.com/wp-content/uploads/2018/05/30164225/572-768x591.png" alt="Logo:" style="width: 80px; height: 60px;">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only"></span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="View/user_own_posts.php">My Posts</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="View/new_post.php">New Post</a>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" href="View/login.php">Login</a>
      </li>
    </ul>
  </div>
</nav>    
<h1>All Blog Posts</h1>



<h2>Search posts:</h2>
<form method="get" action="View/filtered_posts.php">
    <label for="title">Title:</label>
    <input type="text" id="title" name="title"><br>
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br>
    <label for="created_at">Created Date:</label>
    <input type="date" id="created_at" name="created_at"><br>
    <label for="updated_at">Updated Date:</label>
    <input type="date" id="updated_at" name="updated_at"><br>
    <label for="views">Minimum Views:</label>
    <input type="number" id="views" name="views"><br>
    <button type="submit">Search</button>
</form>

<a href="View/user_own_posts.php" class="button">My Posts</a><br><br>
<a href="View/new_post.php" class="button">Add New Post</a><br><br>

<form method="get" action="index.php">
    <label for="records_per_page">Records per page:</label>
    <select id="records_per_page" name="records_per_page">
        <option value="5" <?php echo $records_per_page==5 ? 'selected' : '' ?>>5</option>
        <option value="10" <?php echo $records_per_page==10 ? 'selected' : '' ?>>10</option>
        <option value="20" <?php echo $records_per_page==20 ? 'selected' : '' ?>>20</option>
        <option value="50" <?php echo $records_per_page==50 ? 'selected' : '' ?>>50</option>
        <option value="all" <?php echo $records_per_page == $total_records && $_GET['records_per_page'] === 'all' ? 'selected' : ''; ?>>All</option>

    </select>
    <button type="submit">Set</button>
</form>

<ul>
    <?php
    foreach ($posts as $post) {
        echo "<form><li><a href='View/post.php?id={$post['id']}'><b class='title'>{$post['id']}. Title:</b> {$post['title']}</a></li><li>Author: {$post['username']}</li><br></form>";
    }
    ?>
</ul>

<div>
    <a href="index.php?page=<?php echo $current_page-1==0 ? $current_page : $current_page-1; ?>&records_per_page=<?php echo $_GET['records_per_page']; ?>">Previous</a>
    <?php
    for ($page = 1; $page <= $total_pages; $page++) {
        echo '<a href="index.php?page=' . $page . '&records_per_page=' . $records_per_page . '">' . $page . '</a> ';
    }
    ?>
    <a href="index.php?page=<?php echo $current_page + 1>$total_pages ? $current_page : $current_page + 1; ?>&records_per_page=<?php echo $_GET['records_per_page']; ?>">Next</a>
</div>
<?php require 'View/footer.php';?>
</body>
</html>
