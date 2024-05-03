{% block main %}

<section id="slider">
	<div id="slider-box">
		<div id="bgr" class="bgr" <?= background_image('bgr.jpg') ?>></div>

		<div class="grid justify-items-center gap-8 text-center px-4">
			<h1 id="title" class="txt-wht">SHO<span style="letter-spacing: 0;">P</span></h1>

			<!-- Search box -->
			<form class="page-search" method="GET" autocomplete="off">
				<input type="search" name="search" class="page-search__input" value="<?= $_GET['search'] ?? '' ?>" placeholder="Cerca un prodotto" maxlength="30" onfocus="$(this).next().addClass('focus');" onblur="$(this).next().removeClass('focus');" autocomplete="off">

				<button type="submit" class="page-search__button btn">
					<?= embed_icon('search') ?>
				</button>
			</form>
		</div>

		<a href="#body" class="nav-arr pos-br bot wht scroll"></a>
	</div>

	<div id="cred">
		<img id="cred-ico" src="<?= $slid_des->seller->icon() ?>" />
		<p id="cred-txt">
			<a href="<?= $slid_des->url() ?>"><?= $slid_des->name ?></a> by <a class="txt-c1" href="<?= $slid_des->seller->url() ?>"><?= $slid_des->seller->name ?></a>
		</p>
	</div>
</section>


<div id="body" class="main-wrap">

	<?php if ($warning) { ?>
		<p id="news" class="<?= $warning['color'] ?>"><?= $warning['text'] ?></p>
	<?php } ?>

	<div class="stitle-box mar-t-2">
		<h2 class="component-heading"><span class="txt-light">I NOSTRI</span> <span class="font-semibold txt-c1">PRODOTTI</span></h2>
		<div id="anch1" class="anchor"></div>
	</div>

	<?php $shop->render() ?>

</div>

{% end main %}