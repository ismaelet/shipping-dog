<?php

//get collections
if ($vip)
	$res = DB::select('ID FROM collections WHERE userID = ? ORDER BY position ASC, ID DESC', [$id]);
else
	$res = DB::select('ID FROM collections WHERE userID = ? AND ID IN (SELECT collection FROM designs) ORDER BY position ASC, ID DESC', [$id]);

//generate collections
$i = $vip ? 1 : 0;
while ($row = $res->fetch_assoc()) {
	$i++;

	$coll = new Collection($row['ID']); ?>

	<div id="coll-box<?= $i ?>" class="coll-ele al-top coll-box sort-box <?php if ($i > $collBlock) echo 'hidden'; ?>" data-id="<?= $coll->id; ?>">

		<h3 class="coll-name txt-cen txt-low txt-1rem"><?= $coll->name; ?></h3>

		<a class="collection" href="<?= $coll->url; ?>" style="background-color: <?= $coll->color; ?>;">

			<div class="coll-img bgr <?php if (!$coll->img_cust) echo 'cont'; ?>" <?= $coll->img_m ? background_image($coll->img_m) : ''; ?>></div>

			<div class="coll-cover bgr"></div>

			<div class="coll-title-box">
				<h3 class="coll-title txt-line">Scopri di più</h3>
			</div>
		</a>
	</div>
<?php } ?>