<?php

use Legacy\View\Components\Interactions\FollowButton;

?>

{% block main %}

<section id="head" class="main-wrap" data-seller-id="<?= $seller->id ?>">

	<!-- Cover -->
	<img id="cover" src="<?= $seller->cover() ?>" />

	<form id="head-sec" method="POST" enctype="multipart/form-data">
		<div id="head-cont">

			<!-- Profile icon -->
			<div id="icon-box">
				<img id="icon" src="<?= $seller->icon() ?>" />
			</div>

			<!-- Edit button -->
			<?php if ($vip) { ?>

				<a href="<?= client_url('/manage?id=' . $id) ?>">
					<p id="edit" class="bgr-c1-h">modifica</p>
				</a>

			<?php } ?>

			<!-- Follow button -->
			<div class="flex justify-center mt-8 mb-4">
				<?= FollowButton::render(['seller_id' => $seller->id]) ?>
			</div>

			<div id="title-box" class="text-center">

				<!-- Name -->
				<h1 id="title" class="mar-t-1 txt-3rem"><?= $seller->name ?></h1>

				<!-- Statistics -->
				<p id="store-data" class="mar-b-1 mt-2">
					<?= $productCount ?> Prodotti &middot;
					<?= $likeN ?> Like &middot;
					<?= $follN ?> Follower
				</p>

				<!-- Contacts -->
				<?php if (!empty($row_link['site']) || !empty($row_link['facebook']) || !empty($row_link['instagram']) || !empty($row_link['twitter']) || !empty($row_link['youtube'])) { ?>

					<div id="socials" class="mar-t-1">

						<?php if (!empty($row_link['site'])) { ?>

							<a href="<?= $row_link['site'] ?>" class="social icon-box fill-c1-h">
								<?= embed_icon('website') ?>
							</a>

						<?php } ?>

						<?php if (!empty($row_link['instagram'])) { ?>

							<a href="https://www.instagram.com/<?= $row_link['instagram'] ?>" class="social icon-box fill-c1-h">
								<?= embed_icon('instagram') ?>
							</a>

						<?php } ?>

						<?php if (!empty($row_link['facebook'])) { ?>

							<a href="https://www.facebook.com/<?= $row_link['facebook'] ?>" class="social icon-box fill-c1-h">
								<?= embed_icon('facebook') ?>
							</a>

						<?php } ?>

						<?php if (!empty($row_link['twitter'])) { ?>

							<a href="https://www.twitter.com/<?= $row_link['twitter'] ?>" class="social icon-box fill-c1-h">
								<?= embed_icon('twitter') ?>
							</a>

						<?php } ?>

						<?php if (!empty($row_link['youtube'])) { ?>

							<a href="https://www.youtube.com/<?= $row_link['youtube'] ?>" class="social icon-box fill-c1-h">
								<?= embed_icon('youtube-full') ?>
							</a>

						<?php } ?>

					</div>

				<?php } ?>

				<?php if (!empty($legacySeller->description)) { ?>

					<p id="desc" class="mar-t-2"><?= $legacySeller->description ?></p>

				<?php } ?>

				<?php if (!$seller->status) { ?>

					<p id="wip" class="mar-v-2">Il negozio ancora non è pubblico. A breve verrà valutato ed eventualmente pubblicato.</p>

				<?php } ?>

			</div>
		</div>
	</form>
</section>

<div id="body">

	<!-- Official shop -->

	<?php if ($hasItems) { ?>

		<!-- Shop -->
		<div id="store-pan">
			<div class="main-wrap pad-v-2 mar-t-2">
				<div class="stitle-box">
					<p class="stitle font-semibold">Shop <span class="item-num txt-light"><span class="itemN"></span> risultati</span></p>
				</div>

				<?= $shop->render() ?>
			</div>
		</div>

		<?php if ($collN || $owner) { ?>

			<!-- Collections -->
			<section id="collections">

				<div class="main-wrap sort-pan" data-type="coll">
					<h3 id="coll-title" class="txt-med mar-b-2">Collezioni</h3>

					<div id="collections-grid" class="sortable">

						<?php if ($owner) { ?>

							<div class="coll-ele add">
								<div class="collection clickable lbox-open" data-tar="#coll-add-lbox">
									<div class="center-items hei-100">

										<div class="coll-add-box cen text-center wid-100">
											<div class="coll-add-ico text-neutral-700">
												<?= embed_icon('add') ?>
											</div>
											<p class="coll-add-txt txt-wht">Aggiungi collezione</p>
										</div>
									</div>
								</div>
							</div>

						<?php } ?>

						<?php include('artist/collections.php') ?>

					</div>

					<?php if ($collN > $collBlock) { ?>
						<input type="hidden" id="collN" value="<?= $collN ?>">
						<input type="hidden" id="collBlock" value="<?= $collBlock ?>">
						<input type="hidden" id="collPos" value="<?= $collBlock ?>">

						<div class="text-center">
							<button type="button" id="coll-more" class="sbtn txt-low mar-t-2">Scopri di più</button>
						</div>
					<?php } ?>

					<div class="sort-load bgr">
						<div class="sort-load-icon cen img cont"></div>
					</div>
				</div>

			</section>

		<?php } ?>

		<?php if ($vip) { ?>

			<!-- New collection lbox -->
			<div id="coll-add-lbox" class="lbox">

				<div class="lbox-bgr lbox-close"></div>

				<div class="lbox-form-box">
					<form class="lbox-form" method="POST">
						<h1 class="lbox-title">Aggiungi una collezione</h1>
						<div class="lbox-body">
							<input type="text" name="name" class="input" placeholder="Nome della collezione" required>
						</div>
						<div class="lbox-footer">
							<button type="submit" name="add_folder" class="pbtn btn-full-width">Aggiungi</button>
							<button type="button" class="sbtn close btn-full-width lbox-close">Annulla</button>
						</div>
					</form>
				</div>
			</div>

		<?php } ?>

	<?php } else { ?>

		<!-- If the shop is empty -->
		<div id="empty" class="text-center pad-t-5 pad-b-10 main-wrap">
			<div id="empty-box">
				<p id="empty-title" class="uppercase">Ancora nessun lavoro caricato</p>
				<a href="/manage/portfolio/add?id=<?= $id ?>"><button type="button" class="pbtn btn-full-width mar-t-2">Carica un lavoro</button></a>
			</div>
		</div>

	<?php } ?>

</div>

{% end main %}