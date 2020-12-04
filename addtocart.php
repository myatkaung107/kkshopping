<?php

session_start();

require 'config/config.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}

  if ($_POST) {
    $id=$_POST['id'];
    $qty=$_POST['qty'];

    $pdostmt=$pdo->prepare("SELECT * FROM products WHERE id=".$id);
    $pdostmt->execute();
    $result=$pdostmt->fetch(PDO::FETCH_ASSOC);

    if ($qty > $result['quantity']) {
      echo "<script>alert('Not enough stock');window.location.href='product_detail.php?id=$id';</script>";
    }else {
      if (isset($_SESSION['cart']['id'.$id])) {
        $_SESSION['cart']['id'.$id] += $qty;
      }else {
        $_SESSION['cart']['id'.$id] = $qty;
      }
      // print_r($_POST);
      // print_r($_SESSION);exit();
      header("Location: cart.php");
    }

  }

?>
