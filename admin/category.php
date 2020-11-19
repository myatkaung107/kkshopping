<?php

session_start();
require '../config/config.php';
require '../config/common.php';
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
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
              <div class="card-header">
                <h3 class="card-title">Categories Listings</h3>
              </div>
              <?php
                if (!empty($_GET['pageno'])) {
                  $pageno = $_GET['pageno'];
                }else {
                  $pageno = 1;
                }
                $numOfrecs = 4;
                $offset = ($pageno - 1) * $numOfrecs;

                if (empty($_POST['search'])) {


                  $pdostmt = $pdo -> prepare("SELECT * FROM categories ORDER BY id DESC");
                  $pdostmt-> execute();
                  $rawResult = $pdostmt->fetchAll();

                  $total_pages = ceil(count($rawResult)/$numOfrecs);

                  $pdostmt = $pdo -> prepare("SELECT * FROM categories ORDER BY id DESC LIMIT $offset,$numOfrecs");
                  $pdostmt-> execute();
                  $result = $pdostmt->fetchAll();
                }else {
                  $searchKey = $_POST['search'];

                  $pdostmt = $pdo -> prepare("SELECT * FROM categories WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
                  // print_r($pdostmt);exit();
                  $pdostmt-> execute();
                  $rawResult = $pdostmt->fetchAll();
                  $total_pages = ceil(count($rawResult)/$numOfrecs);

                  $pdostmt = $pdo -> prepare("SELECT * FROM categories WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfrecs");
                  $pdostmt-> execute();
                  $result = $pdostmt->fetchAll();
                }

              ?>
              <!-- /.card-header -->
              <div class="card-body">
                <div>
                  <a href="cat_add.php" type="button" class="btn btn-outline-success"><i class="fas fa-plus-square"></i></a>
                </div>
                <br>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Description</th>
                      <th style="width: 40px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $i = 1;
                      if ($result) {
                        foreach ($result as $value) {
                     ?>
                     <tr>
                       <td><?php echo $i; ?></td>
                       <td><?php echo escape($value['name']) ?></td>
                       <td><?php echo escape(substr($value['description'],0,99)) ?></td>
                       <td>
                         <div class="btn-group">
                           <div class="container">
                             <a href="cat_edit.php?id=<?php echo $value['id'] ?>" type="button" class="btn btn-outline-warning"><i class="far fa-edit"></i></a>
                           </div>
                           <div class="container">
                             <a href="cat_delete.php?id=<?php echo $value['id'] ?>"
                               onclick="return confirm('Are you sure want to delete this item')"
                               type="button" class="btn btn-outline-danger"><i class="far fa-trash-alt"></i></a>
                           </div>
                         </div>
                       </td>
                     </tr>
                    <?php
                      $i++;
                        }

                      }

                    ?>
                  </tbody>
                </table><br>
                <nav aria-label="Page navigation example" style="float:right">
                  <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                    <li class="page-item <?php if($pageno<=1){ echo'disabled';} ?>">
                      <a class="page-link" href="<?php if($pageno<=1){echo '#';}else{ echo "?pageno=".($pageno-1);} ?>">Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                    <li class="page-item <?php if($pageno>=$total_pages){ echo'disabled';} ?>">
                      <a class="page-link" href="<?php if($pageno>=$total_pages){echo '#';}else{ echo "?pageno=".($pageno+1);} ?>">Next</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
                  </ul>
                </nav>
              </div>
              <!-- /.card-body -->

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
