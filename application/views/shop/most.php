
<!-- Page Content -->
<div class="container">
	<?php echo $this->session->flashdata("success"); ?>
	<div id="alert_message"></div>
	<div class="row">
		<div class="col-lg-12">
			<div id="search-bar" class=" mt-4 mb-2">
				<div class="input-group">
					<input type="text" name="search" id="search" class="form-control" placeholder="Search Product..."/>
					<span class="input-group-btn">
						<button class="btn btn-primary" type="button">
							<i class="fa fa-search"></i>
						</button>
					</span>
				</div>
			</div>

			<div  id="content">

				<?= form_open(); ?>
				<div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">
					<ol class="carousel-indicators">
						<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
						<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
						<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
					</ol>
					<!-- <div class="carousel-inner" role="listbox">
						<div class="carousel-item active">
							<img class="d-block img-fluid" src="<?= base_url('style/assets/images/headline/top-brand.jpg')?>" alt="First slide">
						</div>
						<div class="carousel-item">
							<img class="d-block img-fluid" src="<?= base_url('style/assets/images/headline/fab.png')?>" alt="Second slide">
						</div>
						<div class="carousel-item">
							<img class="d-block img-fluid" src="<?= base_url('style/assets/images/headline/projector.png')?>"alt="Third slide">
						</div>
					</div> -->
					<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>
				<?= form_close(); ?>


				<div class="row">
					<?php 
						foreach($sixProducts as $product): 
					?>
					<div class="col-lg-4 col-md-6 mb-4">
						<div class="card h-100">
							<a href="<?= base_url(); ?>index.php/Shop/product/<?= $product->product_id; ?>">
								<img class="card-img-top" src="<?= base_url($product->image_link); ?>" alt="" height="300px">
							</a>
							<div class="card-body">
								<h5 class="card-title">
									<a href="<?= base_url(); ?>index.php/Shop/product/<?= $product->product_id; ?>"><?= $product->product_name; ?></a>
								</h5>
								<h6>
									B$ <?= $product->price; ?>
								</h6>
								<p class="card-text"><?= $product->short_desc; ?></p>
								<p class="card-text">Downloaded: <?= $product->download; ?></p>
							</div>
							<div class="card-footer">
								<!-- <?= form_open(site_url('cart/addToCart'), array( "id" => "addToCart_$product->product_id")); ?> -->
								<!-- <small class="text-muted">&#9733; &#9733; &#9733; &#9733; &#9734;</small> -->
								<input type="number" name="quantity" class="form-control"  value='1' hidden id="quantity_<?= $product->product_id; ?>" />
								<input type="hidden" name="product_id" class="form-control" value="<?= $product->product_id; ?>"/>
								<br>
								<button class="pull-right btn btn-block btn-primary" type="button" onclick="addToCart(<?= $product->product_id; ?>)">
									<span class="fa fa-shopping-cart pull-left"></span>
									Add to cart 		
								</button>
								<!-- <?= form_close(); ?> -->
							</div>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
				<!-- /.row -->
			<?= $link; ?>
			</div>

		</div>
		<!-- /.col-lg-9 /#content -->

	</div>
	<!-- /.row -->

</div>
<!-- /.container -->