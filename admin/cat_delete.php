<?php

require "../config/config.php";

$pdostmt=$pdo->prepare("DELETE FROM categories WHERE id=".$_GET['id']);
$pdostmt->execute();

header('Location: category.php');
?>
