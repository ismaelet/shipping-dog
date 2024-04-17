<?php

use App\View\Components\Interactions\FollowButton;

?>

{% block main %}

<div id="head_block">

	<div id="art_box">
		<div id="art_bgr" class="brb2 br-e <?= $coll->img_cust ? 'cust' : '' ?>" style="background-color: <?= $coll->color ?>">
			<div class="bgr <?php if (!$coll->img_cust) echo 'cont ';
							if (!$coll->img) echo 'bgr-c1' ?>" <?php if ($coll->img) echo temp_bg($coll->img) ?>></div>
		</div>

		<div id="art_head" class="main-wrap">

			<!-- Shop icon -->
			<div id="icon_box">
				<a id="icon" href="<?= $seller->url() ?>" <?= temp_bg($seller->icon()) ?>></a>
			</div>

			<!-- Shop data -->
			<div id="art_data">
				<a href="<?= $seller->url() ?>">
					<h1 id="title"><?= $seller->name ?></h1>
				</a>

				<?php if (!empty($seller->description)) { ?>
					<p id="desc" class="mar-v-1"><?= $seller->description ?></p>
				<?php } ?>

				<!-- Follow button -->
				<?= FollowButton::render(['seller_id' => $seller->id]) ?>
			</div>
		</div>
	</div>
</div>

<div id="body">

	<div class="main-wrap">

		<form id="gallery" method="POST">

			<!-- Title -->
			<div id="stitle-box">
				<h1 class="stitle inlb">
					<a class="txt-c1-h" href="<?= $seller->clientUrl ?>"><?= $seller->name ?></a>
				</h1>

				<h1 class="stitle inlb maro05"> > </h1>

				<?php if ($vip) { ?>

					<div class="inlb">
						<input type="text" id="name-inp" class="inputinlb" name="name" placeholder="Nome collezione" value="<?= $coll->name ?>" data-id="<?= $id ?>">

						<div class="ajax-done-icon"></div>
					</div>

				<?php } else { ?>

					<h1 class="stitle inlb"><?= $coll->name ?>:</h1>

				<?php } ?>
			</div>

			<?php if ($vip) { ?>
				<div class="commands mar-b-2">

					<button type="button" class="sub_txt gre mar-t-1 lbox-open marr1" data-tar="#lbox-add">
						<span class="btn-icon">
							<?= embed_icon('add') ?>
						</span>
						<span class="btn-txt">Aggiungi un lavoro</span>
					</button>

					<!-- Remove designs -->
					<?php if ($remN) { ?>

						<button type="button" class="sub_txt lbox-open yel mar-t-1 marr1" data-tar="#lbox-remove">
							<span class="btn-icon">
								<?= embed_icon('delete') ?>
							</span>
							<span class="btn-txt">Rimuovi un lavoro</span>
						</button>

					<?php } ?>

					<button type="button" id="btn-cover" class="sub_txt blu mar-t-1 lbox-open marr1" data-tar="#img-change">
						<span class="btn-icon">
							<?= embed_icon('gallery') ?>
						</span>
						<span class="btn-txt">Cambia copertina</span>
					</button>

					<button type="submit" name="delete" class="sub_txt red mar-t-1 marr1" onclick="return confirm('Sei sicuro di voler eliminare la collezione?')">
						<span class="btn-icon">
							<?= embed_icon('delete') ?>
						</span>
						<span class="btn-txt">Elimina collezione</span>
					</button>
				</div>

			<?php } ?>

			<!-- Gallery -->
			<div class="folder grid4 grid3-1024w grid2-480w gap1">

				<?php foreach ($products as $product) {
					generate_item_box($product);
				} ?>

			</div>

		</form>
	</div>
</div>

<!-- Lightboxes -->
<?php if ($vip) { ?>

	<!-- Add a design -->
	<div id="lbox-add" class="lbox">

		<div class="lbox-bgr lbox-close"></div>

		<div class="lbox-form-box wide">
			<form class="lbox-form" method="POST">

				<h1 class="lbox-title">Aggiungi lavoro</h1>

				<div class="lbox-body">
					<p class="txt-09rem">Seleziona i lavori da aggiungere alla collezione:</p>

					<div class="gallery txt-cen mar-v-2">

						<?php foreach ($designsToAdd as $design) { ?>

							<label class="lbox-ele-box ele">
								<input type="checkbox" class="hidden" name="add-cbx[]" value="<?= $design->id ?>">

								<div class="lbox-ele clickable">
									<div class="bgr" <?= temp_bg($design->image()) ?>></div>
								</div>
							</label>

						<?php } ?>

					</div>
				</div>

				<div class="lbox-footer txt-cen">
					<button type="submit" name="add" class="pbtn btn-full-width">Aggiungi</button>
					<button type="button" class="sbtn btn-full-width lbox-close">Annulla</button>
				</div>

			</form>
		</div>
	</div>

	<!-- Remove a design -->
	<div id="lbox-remove" class="lbox">

		<div class="lbox-bgr lbox-close"></div>

		<div class="lbox-form-box wide">
			<form class="lbox-form" method="POST">

				<h1 class="lbox-title">Rimuovi dalla collezione</h1>

				<div class="lbox-body center">
					<p class="txt-09rem mar-b-1">Seleziona i design che vuoi rimuovere dalla collezione (non saranno eliminati):</p>

					<div class="gallery">
						<?php foreach ($designsToRemove as $design) { ?>

							<label class="lbox-ele-box ele">
								<input type="checkbox" class="hidden" name="remove-cbx[]" value="<?= $design->id ?>">

								<div class="lbox-ele clickable">
									<div class="bgr cont" <?= temp_bg($design->image()) ?>></div>
								</div>
							</label>

						<?php } ?>

					</div>

				</div>

				<div class="lbox-footer">
					<button type="submit" name="remove" class="pbtn btn-full-width">Rimuovi</button>
					<button type="button" class="sbtn btn-full-width lbox-close">Annulla</button>
				</div>

			</form>
		</div>
	</div>

	<!-- Change collection image -->
	<div id="img-change" class="lbox">
		<div class="lbox-bgr lbox-close"></div>

		<div class="lbox-form-box">
			<form class="lbox-form file-form" method="POST" enctype="multipart/form-data">
				<h1 class="lbox-title">Cambia immagine di copertina</h1>
				<div class="lbox-body">
					<p class="mar-b-1">Seleziona l'immagine di copertina per questa collezione:</p>

					<input type="file" name="file" class="input file-inp">
					<div class="file-load"></div>
					<button type="submit" name="img-change" class="hidden file-submit"></button>

					<button id="file-close" type="button" class="sbtn btn-full-width file-cancel lbox-close mar-t-1">Annulla</button>
				</div>
			</form>
		</div>
	</div>
<?php } ?>

{% end main %}