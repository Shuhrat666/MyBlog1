<?php
session_start();
    
if (isset($_SESSION['admin_password'])) {
    echo 'ses';
    echo "You are already logged in. Redirecting to the index page...";
    header('Refresh: 2; URL=admin_page.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <h2>Login</h2>
    <form action="admin_page.php" method="post">
        <label for="admin_username">Username:</label>
        <input type="text" id="admin_username" name="admin_username" required><br>
        <label for="admin_password">Password:</label>
        <input type="password" id="admin_password" name="admin_password" required><br><br>
        <button type="submit">Login</button>
    </form>

    <?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo 'req';
        $admin_username = $_POST['admin_username'];
        $admin_password = $_POST['admin_password'];
        
        if ($username == 'Shuhrat666'&& $password == '666') {
            echo "Password verification successful.<br>";
            $_SESSION['admin_password'] = $admin_password;
            $_SESSION['admin_username'] = $admin_username;
            setcookie($admin_password, $admin_username, time() + 60);
            header('Location: admin_page.php');
            exit;
        } else {
            echo "Invalid username or password!";
        }
    }

?>
</body>
</html>
