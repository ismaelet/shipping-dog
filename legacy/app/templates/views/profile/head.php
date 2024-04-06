<div id="slider" class="">
	<div id="bgr" class="bgr bgr-c2" <?= temp_bg(user_old()->cover(1024)) ?>></div>
</div>

<div id="slider-data" class="main-wrap txt-cen">

	<img src="<?= public_asset(user_old()->icon) ?>" id="pic" class="img-fill push-center">

	<h1 id="username" class="txt-25rem"><?= user_old()->username ?></h1>

	<p class="mar-t-05">
		<?= user_old()->fname ?>
		<?= empty(user_old()->lname) ? '' : ' ' . user_old()->lname ?>
		<span class="txt-c1 txt-bold "><?= empty(user_old()->fname) && empty(user_old()->lname) ? user_old()->rank_str : ' &middot; ' . user_old()->rank_str ?></span>
	</p>

</div>