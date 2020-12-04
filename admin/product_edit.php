<?php

session_start();
require '../config/config.php';
require '../config/common.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: /admin/login.php');
}

if ($_SESSION['role']!=1) {
  header('Location: /admin/login.php');
}

if ($_POST) {

  if (empty($_POST['name']) || empty($_POST['description']) || empty($_POST['category']) || empty($_POST['quantity'])
      || empty($_POST['price']) || empty($_FILES['image'])) {
    if (empty($_POST['name'])) {
      $name_error="null";
    }
    if (empty($_POST['description'])) {
      $desc_error="null";
    }
    if (empty($_POST['category'])) {
      $cat_error="null";
    }
    if (empty($_POST['quantity'])) {
      $qty_error="null";
    }elseif (is_numeric($_POST['quantity']) !=1) {
      $qty_error="This blank should be integer value";
    }
    if (empty($_POST['price'])) {
      $pri_error="null";
    }elseif (is_numeric($_POST['price']) !=1) {
      $pri_error="This blank should be integer value";
    }
    if (empty($_FILES['image'])) {
      $imageError="null";
    }
  }else {
    if (is_numeric($_POST['quantity']) !=1) {
      $qty_error="This blank should be integer value";
    }
    if (is_numeric($_POST['price']) !=1) {
      $pri_error="This blank should be integer value";
    }
    if ($qty_error =='' && $pri_error =='') {
      if ($_FILES['image']['name'] != null) {
        $file= 'images/'.($_FILES['image']['name']);
        $imageType= pathinfo($file,PATHINFO_EXTENSION);

        if ($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg') {
          echo "<script>alert('Image must be png or jpg or jpeg')</script>";
        }else {
          $name = $_POST['name'];
          $desc = $_POST['description'];
          $category=$_POST['category'];
          $qty = $_POST['quantity'];
          $pri = $_POST['price'];
          $id = $_POST['id'];
          $img = $_FILES['image']['name'];

          move_uploaded_file($_FILES['image']['tmp_name'],$file);

          $pdostmt=$pdo->prepare("UPDATE products SET name=:name,description=:description,category_id=:category,quantity=:quantity,price=:price,image=:image WHERE id=:id");
          $result=$pdostmt->execute(
            array(':name'=>$name,':description'=>$desc,':category'=>$category,':quantity'=>$qty,':price'=>$pri,':image'=>$img,':id'=>$id)
          );
          if ($result) {

            echo "<script>alert('Product is successfully updated');window.location.href='index.php';</script>";
          }
        }
      }else {
        $name = $_POST['name'];
        $desc = $_POST['description'];
        $category=$_POST['category'];
        $qty = $_POST['quantity'];
        $pri = $_POST['price'];
        $id = $_POST['id'];

        $pdostmt=$pdo->prepare("UPDATE products SET name=:name,description=:description,category_id=:category,quantity=:quantity,price=:price WHERE id=:id");
        $result=$pdostmt->execute(
          array(':name'=>$name,':description'=>$desc,':category'=>$category,':quantity'=>$qty,':price'=>$pri,':id'=>$id)
        );
        if ($result) {
          echo "<script>alert('Product is successfully updated');window.location.href='index.php';</script>";
        }
      }
    }

  }
}

  $pdostmt=$pdo->prepare("SELECT * FROM products WHERE id=".$_GET['id']);
  $pdostmt->execute();
  $result=$pdostmt->fetchAll();


?>

<?php
include('header.php');
?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <form class="" action="" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
                  <input type="hidden" name="id" value="<?php echo $result[0]['id'] ?>">
                  <div class="form-group">
                    <label for="">Name</label><p style="color:red"><?php echo empty($name_error) ? '':'*'.$name_error; ?></p>
                    <input type="text" class="form-control" name="name" value="<?php echo escape($result['0']['name']) ?>">
                  </div>
                  <div class="form-group">
                    <label for="">Description</label><p style="color:red"><?php echo empty($desc_error) ? '':'*'.$desc_error; ?></p>
                    <textarea class="form-control" name="description" rows="8" cols="80"><?php echo escape($result['0']['description']) ?></textarea>
                  </div>
                  <div class="form-group">
                    <?php
                    $catStmt=$pdo->prepare("SELECT * FROM categories");
                    $catStmt->execute();
                    $catResult=$catStmt->fetchAll();
                    // print_r($result);exit();
                    ?>
                    <label for="">Category</label><p style="color:red"><?php echo empty($cat_error) ? '':'*'.$cat_error; ?></p>
                    <select class="form-control" name="category">
                      <option value="">Select Categories</option>
                          <?php foreach ($catResult as $value) { ?>

                            <?php if ($value['id'] == $result[0]['category_id']) : ?>
                              <option value="<?php echo $value['id']?>" selected><?php echo $value['name']?></option>
                            <?php else :?>
                              <option value="<?php echo $value['id']?>"><?php echo $value['name']?></option>
                            <?php endif?>
                          <?php } ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="">In Stock</label><p style="color:red"><?php echo empty($qty_error) ? '':'*'.$qty_error; ?></p>
                    <input type="number" class="form-control" name="quantity" value="<?php echo escape($result[0]['quantity']) ?>">
                  </div>
                  <div class="form-group">
                    <label for="">Price</label><p style="color:red"><?php echo empty($pri_error) ? '':'*'.$pri_error; ?></p>
                    <input type="number" class="form-control" name="price" value="<?php echo escape($result[0]['price']) ?>">
                  </div>
                  <div class="form-group">
                    <label for="">Image</label><p style="color:red"><?php echo empty($imageError) ? '':'*'.$imageError; ?></p>
                    <img src="images/<?php echo escape($result[0]['image']) ?>" alt="" width="150" height="150"><br>
                    <input type="file" name="image" value="">
                  </div>
                  <div class="form-group">
                    <a type="button" class="btn btn-outline-warning" href="index.php"><i class="fas fa-backward"></i></a>
                    <input type="submit" class="btn btn-outline-success" name="" value="Update">
                  </div>
                </form>
              </div>
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col-md -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  <?php
  include('footer.html');
  ?>
