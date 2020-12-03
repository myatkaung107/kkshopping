<?php
if (session_status()==PHP_SESSION_NONE) {
	session_start();
}
require 'config/config.php';
require 'config/common.php';

if (!empty($_POST['search'])) {
  setcookie('search',$_POST['search'],time() + (86400*30),"/");
}else {
  if (empty($_GET['pageno'])) {
    unset($_COOKIE['search']);
    setcookie('search', null,-1, '/');
  }
}

?>
<?php include('header.php') ?>
	<?php
		if (!empty($_GET['pageno'])) {
			$pageno=$_GET['pageno'];
		}else {
			$pageno=1;
		}
		$numOfrecs=3;
		$offset=($pageno-1)*$numOfrecs;



		if (empty($_POST['search'])&&empty($_COOKIE['search'])) {
			if (!empty($_GET['category_id'])) {
				$catid=$_GET['category_id'];
				$pdostmt=$pdo->prepare("SELECT * FROM products WHERE category_id=$catid AND quantity > 0 ORDER BY id DESC");
				$pdostmt->execute();
				$rawResult=$pdostmt->fetchAll();

				$total_pages=ceil(count($rawResult)/$numOfrecs);

				$pdostmt=$pdo->prepare("SELECT * FROM products WHERE category_id=$catid AND quantity > 0 LIMIT $offset,$numOfrecs");
				$pdostmt->execute();
				$result=$pdostmt->fetchAll();
			}else {
				$pdostmt=$pdo->prepare("SELECT * FROM products WHERE quantity > 0 ORDER BY id DESC");
				$pdostmt->execute();
				$rawResult=$pdostmt->fetchAll();

				$total_pages=ceil(count($rawResult)/$numOfrecs);

				$pdostmt=$pdo->prepare("SELECT * FROM products WHERE quantity > 0 ORDER BY id DESC LIMIT $offset,$numOfrecs");
				$pdostmt->execute();
				$result=$pdostmt->fetchAll();
			}
		}else {
			$searchKey=!empty($_POST['search']) ? $_POST['search'] : $_COOKIE['search'];
			$pdostmt=$pdo->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%' AND quantity > 0 ORDER BY id DESC");
			$pdostmt->execute();
			$rawResult=$pdostmt->fetchAll();

			$total_pages=ceil(count($rawResult)/$numOfrecs);

			$pdostmt=$pdo->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%' AND quantity > 0 ORDER BY id DESC LIMIT $offset,$numOfrecs");
			$pdostmt->execute();
			$result=$pdostmt->fetchAll();
		}


	?>
	<div class="container">
		<div class="row">
			<div class="col-xl-3 col-lg-4 col-md-5">
				<div class="sidebar-categories">
					<div class="head">Browse Categories</div>
					<ul class="main-categories">
						<li class="main-nav-list">
							<?php
								$catstmt=$pdo->prepare("SELECT * FROM categories ORDER BY id DESC");
								$catstmt->execute();
								$catResult=$catstmt->fetchAll();
							?>
							<?php foreach ($catResult as $key => $value) { ?>
								<a href="index.php?category_id=<?php echo $value['id'] ?>"><span class="lnr lnr-arrow-right"></span><?php echo escape($value['name']) ?></a>
							<?php } ?>
						</li>
				</div>
			</div>
			<div class="col-xl-9 col-lg-8 col-md-7">
				<div class="filter-bar d-flex flex-wrap align-items-center">
					<div class="pagination">
						<a href="?pageno=1" class="active">Frist</a>
						<a <?php if($pageno<=1){ echo'disabled';} ?>
							 href="<?php if($pageno<=1){echo '#';}else{ echo "?pageno=".($pageno-1);} ?>" class="prev-arrow">
							 <i class="fa fa-long-arrow-left" aria-hidden="true"></i>
						 </a>
						<a href="#" class="active"><?php echo $pageno; ?></a>
						<a <?php if($pageno>=$total_pages){ echo'disabled';} ?>
							 href="<?php if($pageno>=$total_pages){echo '#';}else{ echo "?pageno=".($pageno+1);} ?>" class="next-arrow">
							 <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
						 </a>
						<a href="?pageno=<?php echo $total_pages; ?>" class="active">Last</a>
					</div>
				</div>
				<!-- End Filter Bar -->
				<!-- Start Best Seller -->
				<section class="lattest-product-area pb-40 category-list">
					<div class="row">
						<?php
							if ($result) {
								foreach ($result as $key => $value) { ?>
									<div class="col-lg-4 col-md-6">
										<div class="single-product">
											<img class="img-fluid" src="admin/images/<?php echo escape($value['image']) ?>" alt="" style="height:250px;">
											<div class="product-details">
												<h6><?php echo escape($value['name']) ?></h6>
												<div class="price">
													<h6><?php echo escape($value['price']) ?></h6>
													<h6 class="l-through"><?php echo escape($value['price']) ?></h6>
												</div>
												<div class="prd-bottom">
													<form  action="addtocart.php" method="post">
														<input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
								            <input type="hidden" name="id" value="<?php echo escape($value['id']) ?>">
														<input type="hidden" name="qty" value="1">
														<div class="social-info">
															<button type="submit" style="display:contents" class="social-info">
																<span class="ti-bag"></span>
																<p class="hover-text" style="margin-left: -15px;">add to bag</p>
															</button>
														</div>
														<a href="product_detail.php?id=<?php echo $value['id'] ?>" class="social-info">
															<span class="lnr lnr-move"></span>
															<p class="hover-text">view more</p>
														</a>
													</form>
												</div>
											</div>
										</div>
									</div>
						<?php
								}
							}
						?>
					</div>
				</section>
				<!-- End Best Seller -->
				<!-- Start Filter Bar -->

				<!-- End Filter Bar -->
<?php include('footer.php') ?>
