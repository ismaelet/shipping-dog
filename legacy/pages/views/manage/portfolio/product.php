{% block content %}

<form class="form grid-flow pad-v-2" data-spacing="800" method="POST" enctype="multipart/form-data">
	<div class="grid-flow" data-spacing="400">
		<h1 class="txt-2rem">
			<span class="txt-sbold"><?= $oldProduct ? '<span class="txt-light">Modifica:</span> ' . $oldProduct->name : 'Aggiungi un prodotto' ?></span>
		</h1>

		<?php if ($oldProduct) { ?>
			<button type="submit" class="delete-button" name="delete">
				<?= embed_icon('delete') ?>
				Elimina il prodotto
			</button>
		<?php } ?>

		<?php if ($saved) { ?>
			<p class="confirmation">Modifiche al prodotto salvate</p>
		<?php } ?>
	</div>

	<div class="grid-flow" data-spacing="500">
		<?php if ($oldProduct && !$oldProduct->statusId) { ?>
			<p class="error">Il prodotto non è ancora pubblico, ma verrà attivato al più presto</p>
		<?php } ?>

		<div class="form-field">
			<p class="label">Nome:</p>

			<input type="text" class="input" name="name" value="<?= $oldProduct ? $oldProduct->name : '' ?>" placeholder="Nome" required>
		</div>

		<div class="grid-flow" data-columns="2" data-spacing="400">
			<div class="form-field">
				<p class="label">Tipo di prodotto:</p>

				<select class="sel" name="type_id">
					<?php foreach ($types as $type) { ?>
						<option value="<?= $type->id ?>" <?= $oldProduct && $oldProduct->type_id == $type->id ? 'selected' : '' ?>>
							<?= $type->fullName ?>
						</option>
					<?php } ?>
				</select>
			</div>

			<div class="form-field">
				<p class="label">Prezzo:</p>

				<div class="price-input">
					<input type="text" name="price" value="<?= $oldProduct ? $oldProduct->formattedFullPrice : '0.00' ?>" placeholder="Prezzo">
				</div>
			</div>
		</div>

		<div class="form-field">
			<p class="label">Descrizione (opzionale):</p>
			<textarea name="description" maxlength="500" placeholder="Descrizione"><?= $oldProduct ? $product->description() : '' ?></textarea>
		</div>

		<div class="form-field">
			<div>
				<p class="label">Colore predefinito:</p>
				<p>Il colore che viene mostrato di base nell'anteprima del prodotto</p>
			</div>

			<div class="colors">

				<?php foreach ($colors as $color) { ?>

					<div class="color js-color">
						<label class="">
							<input type="radio" class="color__checkbox js-default-color" name="default-color-id" value="<?= $color->id ?>" <?= $oldProduct && $oldProduct->colorId == $color->id ? 'checked' : '' ?> required>
							<div class="color__icon" style="--_color1: #<?= $color->hex ?>; --_color2: #<?= $color->secondHex ?: $color->hex ?>"></div>
						</label>

						<p class="color__label js-default-color-label">
							<?= $color->name ?>
						</p>
					</div>

				<?php } ?>

			</div>
		</div>

		<div class="form-field">
			<div>
				<p class="label">Colori attivi:</p>
				<p>Tutte le varianti di colore possibili per il prodotto</p>
			</div>

			<div class="colors" id="active-colors">

				<?php foreach ($colors as $color) { ?>

					<div class="color js-color">
						<label class="">
							<input type="checkbox" class="color__checkbox js-active-color" name="colors[]" value="<?= $color->slug ?>" <?= $oldProduct && in_array($color->slug, $oldProduct->availableColorSlugs) ? 'checked' : '' ?>>
							<div class="color__icon" style="--_color1: #<?= $color->hex ?>; --_color2: #<?= $color->secondHex ?: $color->hex ?>"></div>
						</label>

						<div class="color__content">
							<p class="color__label js-active-color-label">
								<?= $color->name ?>
							</p>

							<?php

							if ($oldProduct) {
								$oldProduct->setColorId($color->id);
							}

							$imageExists = $oldProduct ? $oldProduct->hasImage : false;

							?>

							<p class="color__upload <?= !$imageExists ? 'color__upload--warning' : '' ?> js-upload"><?= $imageExists ? 'Ricarica' : 'Carica' ?> immagine</p>
							<input type="file" class="color__input js-file-input <?= $oldProduct ? 'js-submit-upload' : '' ?>" name="file-<?= $color->id ?>">
							<button type="submit" class="color__button js-color-button" name="upload" value="<?= $color->id ?>"></button>
						</div>
					</div>

				<?php } ?>

			</div>
		</div>

		<div class="form-field">
			<div>
				<p class="label">Immagine di copertina (opzionale):</p>
				<p>Apparirà di fianco alla descrizione del prodotto</p>
			</div>

			<?php if ($oldProduct && $oldProduct->hasSpecificCoverImage) { ?>

				<label class="replace-image">
					<input type="file" name="cover-image" onchange="$('.js-submit').click()">
					<img class="product-cover-image" src="<?= client_path($oldProduct->coverImage) ?>">
				</label>

			<?php } else { ?>

				<input type="file" class="input" name="cover-image">

			<?php } ?>

		</div>
	</div>


	<div class="flex-group center-content" data-spacing="700">
		<button type="submit" name="<?= $oldProduct ? 'edit' : 'add' ?>" class="pbtn js-submit">
			<?= $oldProduct ? 'Salva' : 'Aggiungi' ?>
		</button>

		<a href="list" class="sbtn">
			<?= ('Indietro') ?>
		</a>
	</div>
</form>

{% end content %}