<?php

$desc = null;
$dett = null;

$res = \Legacy\System\Classes\LegacyDB::select('* FROM items_info WHERE product_id=' . $product->id);

if ($row = $res->fetch_assoc()) {
	$desc = $row['description'];
	$dett = str_replace(array('-', "\r\n"), array('&middot;', '<br>'), $row['details']);
	$dett = !$product->isCustom() ? $dett : null;
} else if (!$product->isCustom()) {
	include_once('descriptions.php');
}

?>

<?php if ($dett || $desc) { ?>
	<div class="mt-8">
		<?php if ($desc) { ?>
			<h3 class="desc_stitle">Descrizione</h3>
			<p class="desc_txt"><?= $desc ?></p><br>
		<?php } ?>

		<?php if ($dett) { ?>
			<h3 class="desc_stitle">Dettagli prodotto</h3>
			<p class="desc_txt"><?= $dett ?></p>
		<?php } ?>
	</div>
<?php } ?>