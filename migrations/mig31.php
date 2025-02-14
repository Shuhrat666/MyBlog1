<?php include 'includes/db.php'; 
require '../includes/password.php'?>

<?php
$pdo = new PDO('mysql:host=localhost;dbname=' . $db_name, $db_username, $db_password);
$stmt=$pdo->prepare(query:"create table blog(id int primary key auto_increment, title varchar(255), text varchar(4095), created_at datetime, updated_at datetime, comments text, views int, status ENUM('published', 'drafted') default 'drafted');");
$stmt->execute();
printf("Created successsfully (Table 'blog')!\n");

$stmt=$pdo->prepare(query:"create table if not exists users(user_id int auto_increment primary key, username varchar(63)
NOT NULL UNIQUE, password varchar(255) NOT NULL, email varchar(64) NOT NULL UNIQUE);");
$stmt->execute();
printf("Created successsfully (Table 'users')!\n");

?>
