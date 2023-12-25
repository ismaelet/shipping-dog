{% extends admin %}

{% block content %}

<!-- Title -->
<section id="title" class="mar-b-1">

	<h1 class="txt-med"><?= View::$title ?></h1>

	<!-- Add button -->
	<?php if ($pageConfig['add']) { ?>

		<a href="add" class="icon-add icon-box">
			<?= icon_embed('add') ?>
		</a>

	<?php } ?>

</section>

<?php if ($pageConfig['search-bar']) { ?>

	<!-- Search bar -->
	<form class="search-bar" method="GET">
		<div class="search-bar__icon--search interactive js-search">
			<?= icon_embed('search') ?>
		</div>

		<input type="text" class="js-search-input" name="search" value="<?= $queryConfig['search'] ?>" placeholder="<?= __('Search in this list') ?>" autocomplete="off">

		<?php if ($queryConfig['search']) { ?>

			<div class="interactive js-search-cancel">
				<?= icon_embed('close') ?>
			</div>

		<?php } ?>

		<button type="submit" class="hidden"></button>
	</form>

<?php } ?>

<!-- Custom filters -->
{% place filter %}

<!-- Custom content -->
{% place custom-content %}

<!-- Pages -->
<?php include('-expands-admin-list/pages.php') ?>

<!-- List -->
<section class="admin-list-wrapper">

	<div class="admin-list" style="grid-template-columns: <?= $pageConfig['grid-columns'] ?>;">

		<?php
		include_once('-expands-admin-list/head.php');
		include_once('-expands-admin-list/body.php');
		?>

	</div>

</section>

<!-- Pages -->
<?php include('-expands-admin-list/pages.php') ?>

{% end content %}