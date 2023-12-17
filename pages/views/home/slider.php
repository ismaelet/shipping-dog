<!-- Slider -->
<section id="slider" data-count="<?= $slidesN = 3 ?>">

	<div class="content">

		<!-- Background images -->
		<div id="slider-background" class="bgr">

			<?php for ($i = 1; $i <= $slidesN; $i++) { ?>
				<div class="slide bgr <?= $i == 1 ? 'active' : '' ?>" <?= background_image('slider/slide-' . $i . '.jpg') ?> data-id="<?= $i ?>"></div>
			<?php } ?>

		</div>

		<!-- Body -->
		<div id="slider-body" class="">

			<h1 class="txt-wht txt-upp">Shipping<br><span class="txt-c1">Dog</span></h1>

			<p class="stitle">T-shirt, abbigliamento e accessori personalizzati</p>

			<a href="<?= client_url('/shop') ?>" class="pbtn">
				<span class="btn-icon small bgr-f" <?= icon_svg('cart') ?>></span>
				<span class="btn-txt">Shop Now</span>
			</a>

		</div>

		<!-- Credit box -->
		<?php /*<div id="slider-credits">

			<div class="icons">

				<?php foreach($slideIDs as $key => $designID) {
					
					$seller = new Seller(DB::find('userID FROM designs WHERE ID=?', [$designID]));

					?>

			        <img src="<?= client_path($seller->icon) ?>" class="img-fill <?= $key == 1 ? 'active' : '' ?>" data-id="<?= $key ?>">

	    		<?php } ?>

	    	</div>

			<div class="names">

				<?php foreach($slideIDs as $key => $designID) {
		
					$design = new Design($designID);
					$seller = $design->seller;

					?>

					<p class="name <?= $key == 1 ? 'active' : '' ?>" data-id="<?= $key ?>">
						<a href="<?= $design->url ?>" class="txt-c1-h"><?= $design->name ?></a>
						by 
						<a href="<?= $seller->clientUrl ?>" class="txt-c1"><?= $seller->name ?></a>
					</p>

				<?php } ?>

			</div>

		</div> */ ?>

	</div>

</section>