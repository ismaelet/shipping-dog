{% extends admin %}

{% block content %}

<form class="content-wrap" id="form" method="POST" enctype="multipart/form-data">

	<!-- Title -->
	<div class="page-title flex gap05">
		<a href="list" class="title-arrow icon-box center-items">
			<?= embed_icon('arrow-simple-left') ?>
		</a>
		<h1 class="add_title"><?= $view->title ?></h1>
	</div>

	<?php if ($updated) { ?>
		<p class="confirmation">Modifiche salvate</p>

	<?php } else if ($added) { ?>

		<p class="confirmation">Elemento aggiunto</p>

	<?php } ?>

	<!-- Page body -->
	<div class="page-body">

		{% place page-body %}

	</div>

	<?php if (!empty($layout['delete']) || !empty($layout['save']) || !empty($layout['preview'])) { ?>

		<!-- Footer -->
		<section class="page-footer <?= $product->id ? '' : 'page-footer_add-item' ?>">

			<?php if (!empty($layout['delete'])) { ?>
				<!-- Delete -->
				<button type="submit" class="sbtn btn-with-icons" name="delete" onclick="return confirm('Confermi di voler eliminare questo elemento? Non potrà essere ripristinato.')">
					Elimina l'elemento
					<span class="text-icon" <?= embed_icon('delete') ?>></span>
				</button>
			<?php } ?>

			<?php if (!empty($layout['preview'])) { ?>
				<!-- Preview -->
				<a class="sbtn preview-btn btn-with-icons" href="<?= $product->clientUrl ?>" target="_blank">
					Esamina
					<span class="text-icon text-icon_big" <?= embed_icon('view') ?>></span>
				</a>
			<?php } ?>

			<?php if (!empty($layout['save'])) { ?>
				<!-- Save -->
				<button type="submit" class="pbtn btn-with-icons" name="submit-form">
					<?= $product->id ? 'Salva' : 'Aggiungi' ?>
					<span class="text-icon" <?= embed_icon($product->id ? 'save' : 'add') ?>></span>
				</button>
			<?php } ?>
		</section>

	<?php } ?>

	<!-- Loading panel -->
	<div class="loading">
		<div class="load-icon place-cen"></div>
	</div>

</form>

{% end content %}