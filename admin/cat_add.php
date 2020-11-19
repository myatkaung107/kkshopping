<?php

session_start();
require '../config/config.php';
require '../config/common.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}

if ($_POST) {
  if (empty($_post['name']) || empty($_POST['description'])) {
    if (empty($_POST['name'])) {
      $name_error="null";
    }
    if (empty($_POST['description'])) {
      $desc_error="null";
    }else {
      $name = $_POST['name'];
      $desc = $_POST['description'];

      $pdostmt= $pdo-> prepare("INSERT INTO categories(name,description) VALUES (:name,:description)");
      $pdostmt->bindValue(':name',$name);
      $pdostmt->bindValue(':description',$desc);
      $result=$pdostmt->execute();

      if ($result) {
        echo "<script>alert('New Category is added');window.location.href='category.php';</script>";
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
                <form class="" action="" method="post" enctype="multipart/form-data">
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
                    <a type="button" class="btn btn-outline-warning" href="category.php"><i class="fas fa-backward"></i></a>
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
