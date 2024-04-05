<?php if ($pagesCount > 1) { ?>

	<form method="GET" class="change-page mar-t-2">

		<input type="hidden" class="js-current-page" name="page">
		<input type="hidden" name="search" value="<?= $queryConfig['search'] ?>">
		<input type="hidden" name="order" value="<?= $queryConfig['order-direction'] ?>">
		<input type="hidden" name="index" value="<?= $queryConfig['order-index'] ?>">
		<input type="hidden" name="filter-status" value="<?= $_GET['filter-status'] ?? '' ?>">
		<button type="submit" class="hidden"></button>

		<?php if ($pageConfig['first-page'] > 2) { ?>
			<p class="change-page__ellipsis">...</p>
		<?php } else if ($pageConfig['first-page'] == 2) { ?>
			<p class="change-page__page js-change-page" data-page="1">1</p>
		<?php } ?>

		<?php for ($i = $pageConfig['first-page']; $i <= $pageConfig['last-page']; $i++) { ?>

			<p class="change-page__page <?= $currentPage == $i ? 'active' : 'js-change-page' ?>" data-page="<?= $i ?>">
				<?= $i ?>
			</p>

		<?php } ?>

		<?php if ($pagesCount > $pageConfig['last-page'] + 1) { ?>
			<p class="change-page__ellipsis">...</p>
		<?php } else if ($currentPage == $pageConfig['last-page'] + 1) { ?>
			<p class="change-page__page js-change-page" data-page="<?= $currentPage ?>"><?= $currentPage ?></p>
		<?php } ?>

	</form>

<?php } ?>