<div id="slider" class="">
	<div id="bgr" class="bgr bgr-c2" <?= background_image(user()->cover[0]) ?>></div>
</div>

<div id="slider-data" class="main-wrap txt-cen">

	<img src="<?= client_path(user()->icon) ?>" id="pic" class="img-fill push-center">

	<h1 id="username" class="txt-25rem"><?= user()->username ?></h1>

	<p class="mar-t-05">
		<?= user()->fname ?>
		<?= empty(user()->lname) ? '' : ' ' . user()->lname ?>
		<span class="txt-c1 txt-bold "><?= empty(user()->fname) && empty(user()->lname) ? user()->rank_str : ' &middot; ' . user()->rank_str ?></span>
	</p>

</div>