{% extends admin %}

{% block content %}

<form method="POST" action="#form" enctype="multipart/form-data" autocomplete="off" id="form" class="file-form">

	<!-- Head -->
	<section class="head bgr-c1">

		<p class="title txt-sbold txt-wht"><?= $page['title'] ?></p>

	</section>

	<!-- Body -->
	<section class="body">

		<?php if ($page['free']) { ?>

			{% place form-body %}

		<?php } else if ($inputs) generate_form($inputs, $entry) ?>

	</section>

	<!-- Footer -->
	<section class="footer">

		<?php if ($page['free']) { ?>

			{% place form-footer %}

		<?php } else { ?>

			<button type="submit" id="submit" class="pbtn <?= $page['upload'] ? 'file-submit' : ''; ?>" name="<?= $page['edit'] ? 'edit' : 'add' ?>" <?php if ($page['confirmation']) { ?> onclick="return confirm('<?= $page['confirmation'] ?>')" <?php } ?>>

				<?= $page['edit'] ? 'Modifica' : 'Aggiungi'; ?>

			</button>

			<?php if ($page['upload']) { ?>
				<div class="file-load"></div>
			<?php } ?>

			<a href="<?= $page['back_url'] ?>" class="sbtn">Indietro</a>

		<?php } ?>

	</section>

</form>

{% end content %}