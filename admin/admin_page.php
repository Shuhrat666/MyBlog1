<?php
include '../includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styles.css">
    <title>My Blog - Admin Page</title>
</head>
<body>
<h1>My Blog - Admin Page</h1>
<form action="admin_page.php" method="post">
    <input type="text" name="title" placeholder="Title"><br>
    <textarea name="content"></textarea><br>
    <button type="submit" name="create">Save</button><br>

    <label for="up">Update section (By id):</label><br>
    <input type="number" name="upid" id="up" placeholder="ID for update">
    <input type="text" name="updated" id="up" placeholder="New Title">
    <textarea name="updatedtext" id="up" placeholder="New text"></textarea>
    <button type="submit" name="update">Update</button><br>

    <label for="box">Delete section (By id):</label><br>
    <input type="number" name="delid" id="del">
    <button type="submit" name="delete">Delete</button>
</form>
<ul>
    <?php
    $newtitle = isset($_POST['title']) ? trim($_POST['title']) : null;
    $newcontent = isset($_POST['content']) ? trim($_POST['content']) : null;
    $updatedtitle = isset($_POST['updated']) ? trim($_POST['updated']) : null;
    $updatedcontent = isset($_POST['updatedtext']) ? trim($_POST['updatedtext']) : null;
    $updatedid = isset($_POST['upid']) ? trim($_POST['upid']) : null;
    $deletedid = isset($_POST['delid']) ? trim($_POST['delid']) : null;
    $time = date('Y-m-d H:i:s');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['create']) && !empty($newtitle) && !empty($newcontent)) {
            $stmt = $pdo->prepare("INSERT INTO blog (title, text, created_at) VALUES (:title, :content, :time);");
            $stmt->execute(['title' => $newtitle, 'content' => $newcontent, 'time' => $time]);
        }

        if (isset($_POST['update']) && !empty($updatedtitle) && !empty($updatedid)) {
            $up_time = date('Y-m-d H:i:s');
            $stmt = $pdo->prepare("UPDATE blog SET title = :title, text = :content, updated_at = :up_time WHERE id = :id");
            $stmt->execute(['title' => $updatedtitle, 'content' => $updatedcontent, 'id' => $updatedid, 'time' => $up_time]);
        }

        if (isset($_POST['delete']) && !empty($deletedid)) {
            $stmt = $pdo->prepare("DELETE FROM blog WHERE id = :id");
            $stmt->execute(['id' => $deletedid]);
        }
    }

    $stmt = $pdo->prepare("SELECT * FROM blog;");
    $stmt->execute();
    $tasks = $stmt->fetchAll();
    foreach ($tasks as $task) {
        echo "<li>{$task['id']}. {$task['title']}</li><li><textarea>{$task['text']}</textarea></li><li><label>Created: {$task['created_at']}</label></li><li><label>Updated: {$task['updated_at']}</label></li>";
    }
    ?>
</ul>
</body>
</html>

