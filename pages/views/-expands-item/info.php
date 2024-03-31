<?php

$desc = null;
$dett = null;

$res = DB::select('* FROM items_info WHERE itemID=' . $oldProduct->id);

if ($row = $res->fetch_assoc()) {
	$desc = $row['description'];
	$dett = str_replace(array('-', "\r\n"), array('&middot;', '<br>'), $row['details']);
} else if (!$oldProduct->isCustom) {
	include_once('descriptions.php');
}

?>

<?php if ($desc || $dett || $oldProduct->coverImage) { ?>

	<section id="info-sec" class="va-cen">

		<?php if ($desc || $dett) { ?>

			<div id="info-lef" class="info-cell half-wrap--left">
				<div id="info-head">
					<div>
						<label id="info-title-desc" class="info-title-box" data-pos="1">
							<input type="checkbox" id="info-cbx-desc" class="hidden info-cbx" checked>
							<h3 class="info-title">Dettagli</h3>
						</label>
						<label id="info-title-rev" class="info-title-box hidden" data-pos="2">
							<input type="checkbox" id="info-cbx-rev" class="hidden info-cbx">
							<h3 class="info-title">Recensioni</h3>
						</label>

						<div id="info-head-line" class="bgr-c1 no-trans"></div>
					</div>
				</div>

				<div id="info-body">

					<div id="info-lef-slide">

						<!-- Description -->
						<div class="info-slide">

							<h3 class="desc_stitle">Descrizione</h3>
							<p class="desc_txt"><?= $desc ?: '-' ?></p><br>

							<?php if (!$oldProduct->isCustom) { ?>
								<h3 class="desc_stitle">Dettagli prodotto</h3>
								<p class="desc_txt"><?= $dett ?: '-' ?></p>
							<?php } ?>

						</div>

						<!--review recap-->
						<?php /* ?>
				<div id="review_left" class="info-slide">
					<form id="rev_box" method="POST">
						<h1 id="rev_scor"><?= format($score, 1, '.', '') ?></h1>

						<div class="star_box">
							<?php $i = 0; while ($i < 5) { $i++;
								if ($i < $score || $i - $score <= .25) $star = 'full';
								else if ($i - $score <= .75) $star = 'half';
								else $star = 'empty' ?>

								<div class="star <?= $star ?>"></div>
							<?php }?>
						</div>

						<p id="rev_num"><?= $scoreN ?> recension<?= ($scoreN == 1) ? 'e' : 'i' ?></p>

						<?php if ($userID) {?>
							<button type="button" id="rev_btn" class="pbtn ora" onClick="$(this).next().show();">Scrivi una recensione</button>

							<div id="revPop">
								<div id="revpop-bgr" onClick="$(this).parent().hide();"></div>
								<div id="revpop-box">
									<?php
										$res_exs = mysqli_query($con, "SELECT ID FROM reviews WHERE userID=$userID AND typeID=$oldProduct->typeID->slug LIMIT 1");
										$res_ava = mysqli_query($con, "SELECT ID FROM orders WHERE buyer=$userID AND typeID=$oldProduct->typeID->slug LIMIT 1");

										if (mysqli_num_rows($res_exs)) {?>

											<p class="txt">Hai già valutato questo prodotto</p><br><br>
											<button type="submit" class="pbtn red" name="rev_delete">Elimina la recensione</button>

										<?php } else if (!mysqli_num_rows($res_ava)) {?>

											<p class="txt">Non hai ancora comprato questo prodotto</p><br><br>
											<button type="button" class="sbtn" onClick="$(this).parents('#revPop').hide();">Chiudi</button>

										<?php } else {?>

											<h1 id="revpop-title"><?= $row_type['name_'.$lang] ?>, valuta il prodotto</h1>
											<input type="hidden" id="score" name="score" value="0">
											<div class="star_box">
												<?php $i = 0; while ($i < 5) { $i++ ?>
													<div class="star post" data-id="<?= $i ?>"></div>
												<?php }?>
											</div><br><br>
											<label class="lbl">Commento:</label>
											<textarea class="textarea" id="comment" name="comment" maxlength="200" placeholder="Lascia un commento per spiegare la tua valutazione."></textarea>
											<button type="submit" name="review" class="pbtn ora">INVIA</button>

									<?php }?>
								</div>
							</div>
						<?php } else {?>
							<button type="submit" id="rev_btn" name="rev_redir" class="pbtn ora">Scrivi una recensione</button>
						<?php }?>
					</form>
				</div>					
					*/ ?>
					</div>
				</div>
			</div>

		<?php } ?>

		<?php if ($oldProduct->coverImage) { ?>

			<div id="info-rig" class="hei-100">

				<img src="<?= $oldProduct->clientCoverImage ?>" class="image-cover">

				<?php /*
			<div id="info-rev-list" class="info-rev">
				<?php if (mysqli_num_rows($res_rev)) { while ($row_rev = mysqli_fetch_assoc($res_rev)) {
					$date = date('d-m-Y', strtotime($row_rev['time'])) ?>
				
					<div class="rev_box">
						<h1 class="rev_name"><?= $row_rev['name'] ?><span class="rev_date"> &middot; <?= $date ?></span></h1>
						
						<div class="star_box">
							<?php $i = 0; while ($i < 5) { $i++ ?>
								<div class="star <?php if ($row_rev['score'] >= $i) echo 'full' ?>"></div>
							<?php }?>
						</div>
						
						<p class="rev_txt"><?= $row_rev['text'] ?></p>
					</div>
				<?php }} else {?>
					<br><br><p class="txt">Ancora nessuna recensione per questo articolo</p>
				<?php }?>
			</div>
			*/ ?>
			</div>

		<?php } ?>

	</section>

<?php } ?>