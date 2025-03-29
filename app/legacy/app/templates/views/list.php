{% extends admin %}

{% block content %}

<!-- Title -->
<section id="title" class="mar-b-1">

	<h1 class="txt-med"><?= $view->title ?></h1>

	<!-- Add button -->
	<?php if ($query['add']) { ?>

		<a href="add<?= $query['addID'] ? '?filter=' . $query['addID'] : $query['addID'] ?>" class="icon-add icon-box">
			<?= embed_icon('add') ?>
		</a>

	<?php } ?>

</section>

<?php if ($query['search-bar']) { ?>

	<!-- Search bar -->
	<form method="GET" id="search-bar" class="grid">

		<div class="icon-box center-items clickable">
			<?= embed_icon('search') ?>
		</div>

		<input type="text" class="input" name="search" value="<?= isset($_GET['search']) ? trim($_GET['search']) : '' ?>" placeholder="Cerca in questa lista" autocomplete="off">

		<button type="submit" class="hidden"></button>

	</form>

<?php } ?>

<!-- List -->
<section id="list">

	<div class="wrapper">

		<?php
		include_once('list/head.php');
		include_once('list/body.php');
		?>

	</div>

</section>

<?php if ($pagesN > 1) { ?>

	<form method="GET" id="pages" class="text-center mar-t-2 padb2">

		<input type="hidden" id="pageN" name="page">
		<input type="hidden" name="search" value="<?= $search ?>">
		<input type="hidden" name="order" value="<?= $query['order'] ?>">
		<input type="hidden" name="index" value="<?= $query['index'] ?>">
		<button type="submit" class="hidden"></button>

		<?php if ($pageMin > 2) { ?>
			<h1 class="txt-09rem maro1 inlb">...</h1>
		<?php } else if ($pageMin == 2) { ?>
			<h1 class="page txt-09rem clickable txt-c1-h inlb" data-page="1">1</h1>
		<?php } ?>

		<?php for ($i = $pageMin; $i <= $pageMax; $i++) { ?>

			<h1 class="page txt-09rem clickable txt-c1-h inlb <?= $pageN == $i ? 'active' : '' ?>" data-page="<?= $i ?>">
				<?= $i ?>
			</h1>

		<?php } ?>

		<?php if ($pagesN > $pageMax + 1) { ?>
			<h1 class="txt-09rem maro1 inlb">...</h1>
		<?php } else if ($pagesN == $pageMax + 1) { ?>
			<h1 class="page txt-09rem clickable txt-c1-h inlb" data-page="<?= $pagesN ?>"><?= $pagesN ?></h1>
		<?php } ?>

	</form>

<?php } ?>

{% end content %}