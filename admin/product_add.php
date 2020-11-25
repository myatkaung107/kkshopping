<?php

session_start();
require '../config/config.php';
require '../config/common.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
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
      $img = $_FILES['image']['name'];

      move_uploaded_file($_FILES['image']['tmp_name'],$file);

      $pdostmt =$pdo->prepare("INSERT INTO products(name,description,category_id,quantity,price,image)
      VALUES (:name,:description,:category,:quantity,:price,:image)");
      $result=$pdostmt->execute(
        array(':name'=>$name,':description'=>$desc,':category'=>$category,':quantity'=>$qty,':price'=>$pri,':image'=>$img)
      );
      if ($result) {
        echo "<script>alert('New Product is successfully added');window.location.href='index.php';</script>";
      }
    }
  }
}

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
                <form class="" action="product_add.php" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
                  <div class="form-group">
                    <label for="">Name</label><p style="color:red"><?php echo empty($name_error) ? '':'*'.$name_error; ?></p>
                    <input type="text" class="form-control" name="name" value="">
                  </div>
                  <div class="form-group">
                    <label for="">Description</label><p style="color:red"><?php echo empty($desc_error) ? '':'*'.$desc_error; ?></p>
                    <textarea class="form-control" name="description" rows="8" cols="80"></textarea>
                  </div>
                  <div class="form-group">
                    <?php
                    $catStmt=$pdo->prepare("SELECT * FROM categories");
                    $catStmt->execute();
                    $catResult=$catStmt->fetchAll();
                    ?>
                    <label for="">Category</label><p style="color:red"><?php echo empty($cat_error) ? '':'*'.$cat_error; ?></p>
                    <select class="form-control" name="category">
                      <option value="">Select Categories</option>
                        <?php
                          foreach ($catResult as $value) {
                        ?>
                            <option value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                        <?php
                          }
                        ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="">In Stock</label><p style="color:red"><?php echo empty($qty_error) ? '':'*'.$qty_error; ?></p>
                    <input type="number" class="form-control" name="quantity" value="">
                  </div>
                  <div class="form-group">
                    <label for="">Price</label><p style="color:red"><?php echo empty($pri_error) ? '':'*'.$pri_error; ?></p>
                    <input type="number" class="form-control" name="price" value="">
                  </div>
                  <div class="form-group">
                    <label for="">Image</label><p style="color:red"><?php echo empty($imageError) ? '':'*'.$imageError; ?></p>
                    <input type="file" name="image" value="">
                  </div>
                  <div class="form-group">
                    <a type="button" class="btn btn-outline-warning" href="index.php"><i class="fas fa-backward"></i></a>
                    <input type="submit" class="btn btn-outline-success" name="" value="Create">
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
