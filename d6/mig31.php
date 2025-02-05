<?php include 'includes/db.php'; ?>
<?php
$pdo = new PDO('mysql:host=localhost;dbname=myblog', 'root', '$Huhrat333');
$stmt=$pdo->prepare(query:"create table blog(id int primary key auto_increment, title varchar(256), text varchar(2048), created_at datetime)");
$stmt->execute();
printf("Created successsfully !\n");
?>
