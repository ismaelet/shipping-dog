{% block content %}

<h1 class="title"><?= View::$title ?></h1>

<div class="grid-flow mar-t-3" data-columns="2" data-spacing="400">

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

{% end content %}