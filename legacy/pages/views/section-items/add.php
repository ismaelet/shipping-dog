{% block form-body %}

<div class="form-field">
	<p class="label">Nome:</p>

	<input type="text" class="input" name="name" value="<?= $product ? $product->name : '' ?>" placeholder="Nome">
</div>

<div class="form-field">
	<p class="label">Negozio:</p>

	<?= input_search($sellerInput) ?>
</div>

<div class="form-field">
	<p class="label">Tipo di prodotto:</p>

	<select class="sel" name="type_id">
		<?php foreach ($types as $type) { ?>
			<option value="<?= $type->id ?>" <?= $product && $product->type_id == $type->id ? 'selected' : '' ?>>
				<?= $type->fullName ?>
			</option>
		<?php } ?>
	</select>
</div>

<div class="form-field">
	<p class="label">Prezzo:</p>

	<div class="price-input">
		<input type="text" name="price" value="<?= $product ? $product->price : '0,00' ?>" placeholder="Prezzo">
	</div>
</div>

<div class="form-field">
	<p class="label">Colore predefinito:</p>

	<div class="colors">

		<?php foreach ($colors as $color) { ?>

			<div class="color js-color">
				<label class="">
					<input type="checkbox" class="color__checkbox js-default-color" name="default-color-id" value="<?= $color->id ?>" <?= $product && $product->color_id == $color->id ? 'checked' : '' ?>>
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
	<p class="label">Colori attivi:</p>

	<div class="colors">

		<?php foreach ($colors as $color) { ?>

			<div class="color js-color">
				<label class="">
					<input type="checkbox" class="color__checkbox js-active-color" name="colors[]" value="<?= $color->slug ?>" <?= $product && in_array($color->slug, $product->availableColorSlugs) ? 'checked' : '' ?>>
					<div class="color__icon" style="--_color1: #<?= $color->hex ?>; --_color2: #<?= $color->secondHex ?: $color->hex ?>"></div>
				</label>

				<div class="color__content">
					<p class="color__label js-active-color-label">
						<?= $color->name ?>
					</p>

					<?php if ($product) { ?>
						<p class="color__upload js-upload">Carica immagine</p>
						<input type="file" class="color__input js-file-input" name="file-<?= $color->id ?>">
						<button type="submit" class="color__button js-color-button" name="upload" value="<?= $color->id ?>"></button>
					<?php } ?>
				</div>
			</div>

		<?php } ?>

	</div>
</div>

<?php if ($product) { ?>
	<div class="form-field">
		<p class="label">Immagine di copertina:</p>
		<input type="file" class="input" name="cover-image">
	</div>
<?php } ?>

{% end form-body %}

{% block form-footer %}

<button type="submit" name="<?= $product ? 'edit' : 'add' ?>" class="pbtn">
	<?= $product ? 'Salva' : 'Aggiungi' ?>
</button>

<a href="<?= $pageConfig['return-url'] ?>" class="sbtn">
	<?= ('Back') ?>
</a>

{% end form-footer %}