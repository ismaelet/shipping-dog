<?php

/*
* Form
*/
// Add to database
function form_db_add($tbl, $fields) {

	$inputs = [];
	$values = [];
	$names = $pholders = '';

	foreach ($fields as $field) {
		if (isset($field[0])) {
			foreach ($field as $input) $inputs[] = $input;
		} else $inputs[] = $field;
	}

	foreach ($inputs as $input) {

		$type = $input['type'] ?? 'text';

		if ($type != 'file' && isset($input['name']) && empty($input['ignore'])) {
			$name = $input['name'];
			$value = isset($_POST[$name]) ? trim($_POST[$name]) : ($input['value'] ?? 0);

			// parse
			if (isset($input['parse'])) switch ($input['parse']) {
				case 'int':
					$value = (int) $value;
					break;
				case 'float':
					$value = (float) str_replace(',', '.', $value);
					break;
			}

			// store values
			$names .= $name . ', ';
			$pholders .= '?, ';
			$values[] = $value;
		}
	}

	$names = rtrim($names, ', ');
	$pholders = rtrim($pholders, ', ');

	$query = $tbl . ' (' . $names . ') VALUES (' . $pholders . ')';

	return DB::add($query, $values);
}

// Edit database
function form_db_update($tbl, $fields, $index, $indexVal) {

	$inputs = [];
	$values = [];
	$query = '';

	foreach ($fields as $field) {
		if (isset($field[0])) {
			foreach ($field as $input) $inputs[] = $input;
		} else $inputs[] = $field;
	}

	foreach ($inputs as $input) {

		$type = $input['type'] ?? 'text';

		if ($type != 'file' && isset($input['name']) && empty($input['ignore'])) {

			$name = $input['name'];
			$value = isset($_POST[$name]) ? trim($_POST[$name]) : ($input['value'] ?? 0);

			// parse
			if (isset($input['parse'])) switch ($input['parse']) {
				case 'int':
					$value = (int) $value;
					break;
				case 'float':
					$value = (float) str_replace(',', '.', $value);
					break;
			}

			// store values
			$values[] = $value;
			$query .= $name . '=?, ';
		}
	}

	$query = rtrim($query, ', ');

	$query = $tbl . ' SET ' . $query . ' WHERE ' . $index . '=?';
	$values[] = $indexVal;

	DB::update($query, $values);
}

// Generate form
function generate_form($fields, $data = false, $error = false, $done = false) {

	foreach ($fields as $field) {

		echo '<div class="form-field">';

		if (isset($field[0]))
			foreach ($field as $input) generate_input($input, $data, $error, $done);

		else
			generate_input($field, $data, $error, $done);

		echo '</div>';
	}
}

function generate_input($input, $data, $error, $done) {

	// basic data
	$type = $input['type'] ?? 'text';
	$name = $input['name'] ?? false;
	$styling = $input['styling'] ?? false;

	// calculate value
	$value = false;

	// if value is explicit
	if (isset($input['value'])) $value = $input['value'];

	// else get from POST, if set
	else if ($name && isset($_POST[$name])) $value = $_POST[$name];

	// else get from MySQL result, if set
	else if ($name && isset($data[$name]) && $type != 'cbx') $value = $data[$name];

	// else if it's a checkbox
	else if ($type == 'cbx') $value = 1;

	// styling
	if ($styling == 'fancy') { ?>

		<div class="fancy-input <?= isset($input['size']) ? 'fancy-input_size' . $input['size'] : '' ?>">

			<?php }

		// start to generate
		// label
		switch ($type) {

			case 'lbl': ?>

				<p class="label"><?= $input['text'] ?>:</p>

			<?php break;

			case 'info': ?>

				<p class="form-text"><?= $input['text'] ?></p>

				<?php break;

			case 'done':

				if ($type == 'done' && $done) { ?>

					<p class="confirmation"><?= $input['text'] ?></p>

				<?php }
				break;

			case 'error':

				if ($input['error'] == $error) { ?>

					<p class="error"><?= $input['text'] ?></p>

				<?php }
				break;

			case 'search':

				input_search($input);

				break;

			case 'file': ?>

				<input type="file" class="inp file" name="<?= $name ?>" <?= isset($input['multiple']) ? 'multiple' : '' ?> <?= empty($input['required']) ? null : 'required' ?>>

			<?php break;

			case 'select':

				if (isset($input['query'])) {
					$selectOptions = DB::select($input['query'])->res;
					$fieldId = $input['field-name'];
					$fieldShow = $input['field-show'];
				} else if (isset($input['array'])) {
					$selectOptions = $input['array'];
					$fieldId = 0;
					$fieldShow = 1;
				}
			?>

				<select class="sel <?= isset($input['affect']) ? 'affect' : '' ?>" <?= isset($input['affect']) ? 'data-affect="' . $input['affect'] . '"' : '' ?> name="<?= $name ?>">

					<?php if (isset($input['empty'])) { ?>

						<option value="0" selected>- <?= __('None') ?> -</option>

					<?php }
					foreach ($selectOptions as $option) { ?>

						<option value="<?= $option[$fieldId] ?>" <?php if ($option[$fieldId] == $value) echo 'selected' ?>>
							<?= $option[$fieldShow] ?>
						</option>

					<?php } ?>

				</select>

			<?php break;

			case 'select-dependent': ?>

				<select class="sel" data-tbl="<?= $input['query-tbl'] ?>" data-filter="<?= $input['query-filter'] ?>" data-id="<?= $input['field-name'] ?>" data-show="<?= $input['field-show'] ?>" data-root="<?= CLIENT_ROOT ?>" name="<?= $name ?>" disabled>
					<option>-</option>
				</select>

			<?php break;

			case 'textarea': ?>

				<textarea class="textarea" name="<?= $name ?>" placeholder="<?= $input['text'] ?? '' ?>" <?= isset($input['maxlength']) ? 'maxlength="' . $input['maxlength'] . '"' : '' ?>><?=
																																															$value ?: ''
																																															?></textarea>

			<?php break;

			case 'cbx': ?>

				<label class="
					checkbox
					<?= $input['custom-class'] ?? '' ?>
					<?= isset($input['size']) ? 'inp-size' . $input['size'] : '' ?>
				">

					<input type="checkbox" name="<?= $name ?>" value="<?= $value ?>" <?= !empty($input['checked']) || ($name && isset($_POST[$name])) || ($name && isset($data[$name]) && $data[$name] == $value) ? 'checked' : '' ?> <?= isset($input['required']) ? 'required' : '' ?>>

					<span></span>

					<p><?= $input['text'] ?></p>

				</label>

			<?php break;

			case 'img': ?>

				<img class="image-fit" src="<?= $input['src'] ?>" alt="">

			<?php break;

			case 'btn': ?>

				<button type="<?= !empty($input['submit']) ? 'submit' : 'button' ?>" class="<?= $input['btn-type'] ?? 'pbtn' ?> <?= isset($input['load']) ? 'file-submit' : '' ?> <?= $input['custom-class'] ?? '' ?>" name="<?= $name ?: 'submit' ?>" data-size="<?= $input['size'] ?? 4 ?>">
					<?= $input['text'] ?? '' ?>
				</button>

				<?php if (isset($input['load'])) { ?>

					<div class="file-load"></div>

				<?php } ?>

			<?php break;

			default: ?>

				<input type="<?= $type ?>" class="
					<?= $input['custom-class'] ?? '' ?>
				" name="<?= $name ?>" value="<?= $value ?>" placeholder="<?= $styling ? '' : ($input['text'] ?? '') ?>" <?= isset($input['minlength']) ? 'minlength="' . $input['minlength'] . '"' : '' ?> <?= isset($input['maxlength']) ? 'maxlength="' . $input['maxlength'] . '"' : '' ?> <?= isset($input['min']) ? 'min="' . $input['min'] . '"' : '' ?> <?= isset($input['max']) ? 'max="' . $input['max'] . '"' : '' ?> <?= isset($input['step']) ? 'step="' . $input['step'] . '"' : '' ?> <?= isset($input['required']) ? 'required' : '' ?> <?= isset($input['readonly']) ? 'readonly' : '' ?> <?= isset($input['required']) ? 'required' : '' ?> data-size="<?= $input['size'] ?? 4 ?>" <?php
																																																																																																																																																																									if (isset($input['data']))
																																																																																																																																																																										foreach ($input['data'] as $dataName => $dataValue) echo 'data-' . $dataName . '="' . $dataValue . '" '
																																																																																																																																																																									?>>

			<?php break;
		}

		// styling closing tag
		if ($styling == 'fancy') { ?>

			<p class="fancy-input-lbl"><?= $input['text'] ?></p>

		</div>

	<?php }
	}

	/*
	* (string) search: the parcial name of file that fill be fetched (the full name will be search-*name*)
	* (string) name: the input's name
	* (string) text: the placeholder text
	* ? (string) name-show: the name of the visible field, used in custom situation, for ex. to add a new entry based on the typed name
	* ? (mixed) value: the actual value of the input
	* ? (mixed) value-show: the shown value of the input
	* ? (string) custom-classes: custom classes that the whole block will have
	* ? (bool) required: set if the input is required
	* ? (bool) title: if set, it will show the shown input content on hover
	*/

	function input_search($input) {
		$showValue = $input['value-show'] ?? '';
	?>

	<div class="ajax-search pos-rel <?= $input['custom-classes'] ?? '' ?>" data-endpoint="<?= $input['search'] ?>">

		<input type="hidden" class="ajax-search-id" name="<?= $input['name'] ?>" value="<?= $input['value'] ?? '' ?>" <?= isset($input['required']) ? 'required' : '' ?>>

		<input type="text" class="inp ajax-search-text" <?= isset($input['name-show']) ? 'name="' . $input['name-show'] . '"' : '' ?> value="<?= $showValue ?>" <?= !empty($input['title']) ? 'title="' . $showValue . '"' : '' ?> placeholder="<?= $input['text'] ?? '' ?>" <?= isset($input['required']) ? 'required' : '' ?> autocomplete="off">

		<div class="ajax-search-ddown">
			<div class="ajax-search-box"></div>
		</div>
	</div>

<?php }
