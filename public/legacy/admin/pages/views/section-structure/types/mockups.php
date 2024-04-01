{% block content %}

<h1 class="section-heading mar-b-3"><?= View::$title ?></h1>

<div class="grid gap-8">
	<div class="panel">
		<h3 class="text-component-heading">Posizionamento</h3>

		<div class="grid grid-cols-2 gap-12 items-center">
			<form method="POST" class="grid gap-2">
				<div class="">
					<label class="label" for="siz_x_def">Dimensione di default</label>
					<div class="range-input">
						<input type="range" id="siz_x_def" name="siz_x_def" value="<?= $mockupData->siz_x_def ?>" min="0" max="100" step="1">
						<p class="range-text"><span><?= $mockupData->siz_x_def ?></span> %</p>
					</div>
				</div>

				<div class="">
					<label class="label" for="pos_x_min">Posizione orizzontale minima</label>
					<div class="range-input">
						<input type="range" id="pos_x_min" name="pos_x_min" value="<?= $mockupData->pos_x_min ?>" min="0" max="100" step="1">
						<p class="range-text"><span><?= $mockupData->pos_x_min ?></span> %</p>
					</div>
				</div>

				<div class="">
					<label class="label" for="siz_x_max">Dimensione orizzontale massima</label>
					<div class="range-input">
						<input type="range" id="siz_x_max" name="siz_x_max" value="<?= $mockupData->siz_x_max ?>" min="0" max="100" step="1">
						<p class="range-text"><span><?= $mockupData->siz_x_max ?></span> %</p>
					</div>
				</div>

				<div class="">
					<label class="label" for="pos_x_def">Posizione orizzontale di default</label>
					<div class="range-input">
						<input type="range" id="pos_x_def" name="pos_x_def" value="<?= $mockupData->pos_x_def ?>" min="0" max="100" step="1">
						<p class="range-text"><span><?= $mockupData->pos_x_def ?></span> %</p>
					</div>
				</div>

				<div class="">
					<label class="label" for="pos_y_min">Posizione verticale minima</label>
					<div class="range-input">
						<input type="range" id="pos_y_min" name="pos_y_min" value="<?= $mockupData->pos_y_min ?>" min="0" max="100" step="1">
						<p class="range-text"><span><?= $mockupData->pos_y_min ?></span> %</p>
					</div>
				</div>

				<div class="">
					<label class="label" for="siz_y_max">Dimensione verticale massima</label>
					<div class="range-input">
						<input type="range" id="siz_y_max" name="siz_y_max" value="<?= $mockupData->siz_y_max ?>" min="0" max="100" step="1">
						<p class="range-text"><span><?= $mockupData->siz_y_max ?></span> %</p>
					</div>
				</div>

				<div class="">
					<label class="label" for="pos_y_def">Posizione verticale di default</label>
					<div class="range-input">
						<input type="range" id="pos_y_def" name="pos_y_def" value="<?= $mockupData->pos_y_def ?>" min="0" max="100" step="1">
						<p class="range-text"><span><?= $mockupData->pos_y_def ?></span> %</p>
					</div>
				</div>

				<button type="submit" class="button mt-8" name="edit">Salva</button>
			</form>

			<div class="preview-image grid stack-content" style="--default-x-size: <?= $mockupData->siz_x_def ?>; --max-x-size: <?= $mockupData->siz_x_max ?>; --max-y-size: <?= $mockupData->siz_y_max ?>; --default-x-position: <?= $mockupData->pos_x_def ?>; --starting-x-position: <?= $mockupData->pos_x_min ?>; --default-y-position: <?= $mockupData->pos_y_def ?>; --starting-y-position: <?= $mockupData->pos_y_min ?>;">
				<img src="<?= public_client_path('/uploads/product_types/mockups/bgr/grey.png') ?>" alt="">
				<img src="<?= public_client_path($mockupData->clothingImage()) ?>" alt="">

				<div class="max-printable-area"></div>
				<div class="default-printable-area"></div>

				<?php if ($mockupData->overlayImage()) { ?>

					<img src="<?= public_client_path($mockupData->overlayImage()) ?>" alt="">

				<?php } ?>

			</div>
		</div>
	</div>

	<div class="grid-flow" data-columns="2" data-spacing="400">

		<?php foreach ($mockups as $mockup) { ?>

			<div class="mockup panel" id="<?= $mockup->colorId . '-' . $mockup->positionId ?>">
				<h3 class="block-title"><?= $mockup->nameWithoutType ?></h3>

				<div class="grid-flow mar-t-1" data-columns="2" data-spacing="400">
					<!-- Background image -->
					<form class="mockup__background background-fill" method="POST" enctype="multipart/form-data">
						<label class="mockup__image fill-all replace-image <?= !$mockup->hasBackgroundImage ? 'replace-image--fixed' : '' ?>">
							<input type="file" name="file" class="js-submit-on-change">
							<input type="hidden" name="image-url" value="<?= $mockup->backgroundImage ?>">
							<input type="hidden" name="type-id" value="<?= $mockup->typeId ?>">
							<input type="hidden" name="color-id" value="<?= $mockup->colorId ?>">
							<input type="hidden" name="position-id" value="<?= $mockup->positionId ?>">
							<img class="mockup__image image-fill" <?= $mockup->hasBackgroundImage ? 'src="' . file_public_url($mockup->backgroundImage) . '"' : '' ?> alt="">
						</label>
					</form>

					<!-- Front image -->
					<form class="mockup__background background-fill" method="POST" enctype="multipart/form-data">
						<label class="mockup__image fill-all replace-image <?= !$mockup->hasFrontImage ? 'replace-image--fixed' : '' ?>">
							<input type="file" name="file" class="js-submit-on-change">
							<input type="hidden" name="image-url" value="<?= $mockup->frontImage ?>">
							<input type="hidden" name="type-id" value="<?= $mockup->typeId ?>">
							<input type="hidden" name="color-id" value="<?= $mockup->colorId ?>">
							<input type="hidden" name="position-id" value="<?= $mockup->positionId ?>">
							<img class="mockup__image image-fill" <?= $mockup->hasFrontImage ? 'src="' . file_public_url($mockup->frontImage) . '"' : '' ?> alt="">
						</label>
					</form>

				</div>
			</div>

		<?php } ?>

	</div>
</div>

{% end content %}