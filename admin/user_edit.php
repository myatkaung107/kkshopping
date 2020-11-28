<?php
require '../config/config.php';
require '../config/common.php';
session_start();

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: /admin/login.php');
}
if ($_SESSION['role']!=1) {
  header('Location: /admin/login.php');
}
if ($_POST) {
  if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['phone']) || empty($_POST['address']) || empty($_POST['password']) || strlen($_POST['password'])<4) {
    if (empty($_POST['name'])) {
      $name_error = 'Fill in name';
    }
    if (empty($_POST['email'])) {
      $email_error = 'Fill in email';
    }elseif (!empty($_POST['password']) && strlen($_POST['password']) < 4) {
      $password_error = 'Password must be at least 4 characters';
    }

  }else {
    $id = $_GET['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
    if (empty($_POST['role'])) {
      $role=0;
    }else {
      $role=1;
    }

    $pdostmt = $pdo -> prepare("SELECT * FROM users WHERE email = :email AND id!=:id");
    $pdostmt -> bindValue(':email',$email);
    $pdostmt -> bindValue(':id',$id);
    $pdostmt -> execute();
    $user = $pdostmt -> fetch(PDO::FETCH_ASSOC);

    if ($user) {
      echo "<script>alert('Email already used')</script>";
    }else {
      if ($password !=null) {
        $pdostmt= $pdo-> prepare("UPDATE users SET name='$name',email='$email',password='$password',role='$role',phone='$phone',address='$address' WHERE id='$id'");
      }else {
        $pdostmt= $pdo-> prepare("UPDATE users SET name='$name',email='$email',role='$role',phone='$phone',address='$address' WHERE id='$id'");
      }
      $result=$pdostmt->execute();
      if ($result) {
        echo "<script>alert('User is updated');window.location.href='user_list.php';</script>";
      }
    }
  }
}
  $pdostmt=$pdo->prepare("SELECT * FROM users WHERE id=".$_GET['id']);
  $pdostmt->execute();
  $result=$pdostmt->fetchAll();

?>
<?php include('header.php'); ?>
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
                  <label for="">Name</label><p style="color:red"><?php echo empty($name_error) ? '':'*'.$name_error ?></p>
                  <input type="text" class="form-control" name="name" value="<?php echo escape($result[0]['name']) ?>" >
                </div>
                <div class="form-group">
                  <label for="">Email</label><p style="color:red"><?php echo empty($email_error) ? '':'*'.$email_error ?></p>
                  <input type="email" class="form-control" name="email" value="<?php echo escape($result[0]['email']) ?>" >
                </div>
                <div class="form-group">
                  <label for="">Phone</label><p style="color:red"><?php echo empty($phone_error) ? '':'*'.$phone_error ?></p>
                  <input type="text" class="form-control" name="phone" value="" >
                </div>
                <div class="form-group">
                  <label for="">Address</label><p style="color:red"><?php echo empty($address_error) ? '':'*'.$address_error ?></p>
                  <input type="text" class="form-control" name="address" value="" >
                </div>
                <div class="form-group">
                  <label for="">Password</label><p style="color:red"><?php echo empty($password_error) ? '':'*'.$password_error ?></p>
                  <input type="password" class="form-control" name="password" value="<?php echo escape($result[0]['password']) ?>" >
                </div>
                <div class="form-group">
                  <label for="vehicle3">Role</label><br>
                  <input type="checkbox" name="role" value="1">
                </div>
                <div class="form-group">
                  <a type="button" class="btn btn-outline-warning" href="user_list.php"><i class="fas fa-backward"></i></a>
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

<?php include('footer.html') ?>
