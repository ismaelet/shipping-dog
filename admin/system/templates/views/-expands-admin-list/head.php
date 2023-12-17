<!-- Table head -->
<div class="admin-list__head">

	<?php foreach ($fields as $field) {
		$type = $field['type'] ?? 'show';

		if (in_array($type, ['show', 'find', 'btn', 'img', 'custom', 'boolean-icon'])) {
			$sort = $field['name'] ?? null;
			$sort = !empty($field['not-sortable']) ? null : $sort;
			$active = $sort && $sort == $queryConfig['order-index'];
	?>

			<form class="admin-list__field <?= $type == 'boolean-icon' ? 'admin-list__field--centered' : '' ?> <?= $sort ? 'interactive js-sort-list' : '' ?>" method="GET">

				<?php if ($sort) { ?>
					<input type="hidden" name="search" value="<?= $queryConfig['search'] ?>">
					<input type="hidden" name="order" value="<?= $queryConfig['order-direction'] == 'DESC' && $active ? 'ASC' : 'DESC' ?>">
					<input type="hidden" name="index" value="<?= $sort ?>">
				<?php } ?>

				<p class="list-field-name txt-line">
					<?= $field['title'] ?? '' ?>
					<?php if ($active) echo $queryConfig['order-direction'] == 'DESC' ? '&#x25BE;' : '&#x25B4;' ?>
				</p>
			</form>

	<?php }
	} ?>

</div>