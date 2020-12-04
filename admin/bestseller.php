<?php

session_start();
require '../config/config.php';
require '../config/common.php';
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: admin/login.php');
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
                <h3 class="card-title">Bestseller Items</h3>
              </div>
                <?php
                $currentDate=date("Y-m-d");
                $pdostmt=$pdo->prepare("SELECT * FROM sale_order_details GROUP BY product_id HAVING SUM(quantity)>5 ORDER BY id DESC");
                $pdostmt->execute();
                $result=$pdostmt->fetchAll();
                ?>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered" id="d-table">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Product</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $i= 1;
                      if ($result) {
                        foreach ($result as $value) {
                      ?>
                      <?php
                      $productStmt=$pdo->prepare("SELECT * FROM products WHERE id=".$value['product_id']);
                      $productStmt->execute();
                      $productResult=$productStmt->fetchAll();
                      ?>
                        <tr>
                          <td><?php echo $i; ?></td>
                          <td><?php echo escape($productResult[0]['name']) ?></td>
                        </tr>
                      <?php
                      $i++;
                        }
                      }
                      ?>
                  </tbody>
                </table><br>

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
  <script>
  $(document).ready(function() {
    $('#d-table').DataTable({
      "pagingType": "full_numbers"
    });
  } );
  </script>
