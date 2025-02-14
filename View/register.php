<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <h2>Register</h2>
    <form action="register.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        <button type="submit">Register</button>
    </form>

    <?php
    require '../includes/db.php';
    require '../Controller/userController.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $email = trim($_POST['email']);

        if (registerUser($pdo, $username, $password, $email)) {
            echo "Registration successful. You can now <a href='login.php'>log in</a>.";
        } else {
            echo "Error: Username already exists. Please choose a different username.";
        }
    }
    ?>
</body>
</html>
