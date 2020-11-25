<?php

require '../config/config.php';

$pdostmt=$pdo->prepare("DELETE FROM products WHERE id=".$_GET['id']);
$pdostmt->execute();

header('Location: index.php');

?>
