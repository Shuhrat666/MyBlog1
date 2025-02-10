<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="styles/styles.css">
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
    include 'includes/db.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username']);
        $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
        $email = trim($_POST['email']);
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            echo "Error: Username already exists. Please choose a different username.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
            if ($stmt->execute(['username' => $username, 'password' => $password, 'email' => $email])) {
                $id = $pdo->lastInsertId();
                setcookie('user_id', $id, time() + 60);
                echo "Registration successful. You can now <a href='login.php'>log in</a>.";
            } else {
                echo "Error: Registration failed.";
            }
        }
    }
    ?>
</body>
</html>
