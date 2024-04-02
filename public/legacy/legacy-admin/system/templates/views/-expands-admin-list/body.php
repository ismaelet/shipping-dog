<?php

foreach ($listRows as $result) {
	$rowId = $result[count($result) - 1]['value'];
?>

	<form method="POST" class="admin-list__row" id="row<?= $rowId ?>">

		<?php

		// if set, make the row a link
		if (isset($pageConfig['row-action'])) {
			$rowAction = str_replace('{{id}}', $rowId, $pageConfig['row-action']);
			$listFilters = explode('?', $_SERVER['REQUEST_URI'], 2)[1] ?? false;

			if ($listFilters) {
				$listFilters = urlencode($listFilters);
				$rowAction .= '&list-filters=' . $listFilters;
			}

			echo '<a class="admin-list__row-link" href="' . $rowAction . '">';
		}

		// generate the fields
		foreach ($result as $entry) {

			switch ($entry['type']) {

				case 'show':
				case 'find':
					/*
					* Show & find fields
					*/
					$fullContentSnippet = $entry['overflow'] ? '<p class="text-full">' . $entry['value'] . '</p>' : '';
					$wrapContentClass = $entry['wrap'] ? 'wrap-content' : 'nowrap-content';
					$classesSnippet = implode(
						' ',
						array_filter(['field-' . $entry['name'], ($entry['overflow'] ? 'overflow' : ''), ...$entry['custom-classes'], $wrapContentClass])
					);
		?>

					<div class="admin-list__field text-field <?= $classesSnippet  ?>">
						<p class="text-short"><?= $entry['showed-value'] ?></p>
						<?= $fullContentSnippet ?>
					</div>

				<?php
					break;

				case 'img':
					/*
					* Image field
					*/
					$maxSizeSnippet = $entry['max-size'] ? '--_max-size: ' . $entry['max-size'] . 'rem;' : '';
				?>
					<div class="admin-list__field list-image-field">
						<img class="list-img" src="<?= $entry['src'] ?>" alt="" loading="lazy" style="<?= $maxSizeSnippet ?>">
					</div>
				<?php
					break;

				case 'boolean-icon':
					/*
					* Boolean icon field
					*/
				?>
					<div class="admin-list__field">
						<?php if ($entry['value']) { ?>
							<div class="admin-list__icon active"><?= icon_embed('correct') ?></div>
						<?php } else { ?>
							<div class="admin-list__icon inactive"><?= icon_embed('wrong') ?></div>
						<?php } ?>
					</div>

				<?php
					break;

				case 'custom':
					/*
					* Custom field
					*/
				?>
					<div class="admin-list__field">
						<?= $entry['content'] ?>
					</div>

				<?php
					break;

				case 'hidden':
					/*
					* Hidden field
					*/
				?>
					<input type="hidden" name="<?= $entry['name'] ?>" value="<?= $entry['value'] ?>">

				<?php
					break;

				case 'btn':
					/*
					* Button field
					*/
				?>

					<div class="admin-list__field">
						<div class="admin-list_buttons">

							<?php if (isset($entry['buttons'])) foreach ($entry['buttons'] as $button) {

								if ($button['type'] == 'link') {
									echo '
									<a
										href="' . $button['url'] . '"
										class="admin-list__button ' . $button['custom-class'] . '"
										data-color="' . $button['color'] . '" ' .
										($button['download'] ? 'download' : '') . '>
											' . $button['title'] . '
									</a>';
								} else if ($button['type'] == 'button') { ?>

									<button type="<?= $button['name'] ? 'submit' : 'button' ?>" <?= $button['name'] ? 'name="' . $button['name'] . '"' : '' ?> class="admin-list__button <?= $button['custom-class'] ?>" data-color="<?= $button['color'] ?>" <?php if ($button['warning']) { ?> onclick="return confirm('<?= $button['warning'] ?>')" <?php } ?>>

										<?= $button['title'] ?>

									</button>

								<?php } ?>

							<?php } ?>

						</div>
					</div>

		<?php
					break;
			}
		} ?>

	</form>

	<?php if (isset($pageConfig['row-action'])) echo '</a>' ?>

<?php }
