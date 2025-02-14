<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <h2>Login</h2>
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Login</button>
    </form>

    <?php
    require '../includes/db.php';
    require '../Controller/userController.php';

    session_start();
    
    if (isset($_SESSION['user_id'])) {
        echo "You are already logged in. Redirecting to the index page...";
        header('Refresh: 2; URL=/');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $user = loginUser($pdo, $username, $password);

        if ($user) {
            echo "Password verification successful.<br>";
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            setcookie('user_id', $user['user_id'], time() + 60);
            header('Location: /index.php');
            exit;
        } else {
            echo "Invalid username or password! If you are not registered yet, you can <a href='register.php'>register</a> now!";
        }
    }
    ?>
</body>
</html>
