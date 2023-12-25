{% block main %}

<section id="slider">
	<div id="slider-box">
		<div id="bgr" class="bgr" <?= background_image('bgr.jpg') ?>></div>

		<div id="title-box" class="txt-cen cenY rel pad-h-5">
			<h1 id="title" class="mar-b-4 txt-wht">SHO<span style="letter-spacing: 0;">P</span></h1>

			<div id="srch-pan" class="inlb">
				<form class="srch_box" method="GET" autocomplete="off">
					<div class="srch_row">

						<div class="srch_cell left">
							<input type="text" name="search" id="slider-search-inp" class="inp" value="<?= $_GET['search'] ?? '' ?>" placeholder="Cerca un prodotto" maxlength="30" onfocus="$('#slider-search-btn').addClass('focus');" onblur="$('#slider-search-btn').removeClass('focus');" autocomplete="off">
						</div>

						<div class="srch_cell right">
							<button type="submit" id="slider-search-btn" class="srch_btn bgr-c1">
								<span class="bgr" <?= background_image(ICONS_DIR . 'actions/srch_wht.png') ?>></span>
							</button>
						</div>

					</div>
				</form>
			</div>
		</div>

		<a href="#body" class="nav-arr pos-br bot wht scroll"></a>
	</div>

	<div id="cred">
		<div id="cred-ico" <?= background_image($slid_des->seller->icon) ?>></div>
		<p id="cred-txt"><a href="<?= $slid_des->url ?>"><?= $slid_des->name ?></a> by <a class="txt-c1" href="<?= $slid_des->seller->clientUrl ?>"><?= $slid_des->seller->name ?></a></p>
	</div>
</section>


<div id="body" class="main-wrap">

	<?php if ($warning) { ?>
		<p id="news" class="<?= $warning['color'] ?>"><?= $warning['text'] ?></p>
	<?php } ?>

	<div class="stitle-box mar-t-2">
		<h2 class="stitle"><span class="txt-light">I NOSTRI</span> <span class="txt-bold txt-c1">PRODOTTI</span></h2>
		<div id="anch1" class="anchor"></div>
	</div>

	<?php $shop->render() ?>

</div>

{% end main %}