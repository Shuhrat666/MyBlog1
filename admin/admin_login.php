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
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Login</button>
    </form>

    <?php

    session_start();
    
    if (isset($_SESSION['password'])) {
        echo "You are already logged in. Redirecting to the index page...";
        header('Refresh: 2; URL=admin_page.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        
        if ($username == 'Shuhrat666'&& $password == '666') {
            echo "Password verification successful.<br>";
            $_SESSION['password'] = $password;
            $_SESSION['username'] = $username;
            setcookie($password, $username, time() + 60);
            header('Location: admin_page.php');
            exit;
        } else {
            echo "Invalid username or password!";
        }
    }
?>
</body>
</html>
