<?php include 'includes/db.php'; ?>
<?php
$pdo = new PDO('mysql:host=localhost;dbname=myblog', 'root', '$Huhrat333');
$stmt=$pdo->prepare(query:"create table blog(id int primary key auto_increment, title varchar(256), text varchar(2048), created_at datetime)");
$stmt->execute();
printf("Created successsfully (Table 'blog')!\n");

$stmt=$pdo->prepare(query:"create table if not exists users(user_id int auto_increment primary key, username varchar(64)
NOT NULL UNIQUE, password varchar(10) NOT NULL, email varchar(64) NOT NULL UNIQUE);");
$stmt->execute();
printf("Created successsfully (Table 'users')!\n");

?>
