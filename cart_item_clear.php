<?php

session_start();

unset($_SESSION['cart']['id'.$_GET['id']]);

header("Location: cart.php");
?>
