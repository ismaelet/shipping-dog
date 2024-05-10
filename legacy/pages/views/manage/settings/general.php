{% block content %}
<div class="grid columns-40rem gap-8">
	<form class="form" method="POST">

		<h1 class="stitle br-c1">Nome e descrizione</h1>

		<div class="grid gap1">

			<div class="grid gap1 pad-t-2">
				<p class="lbl">Nome:</p>
				<input type="text" class="input" name="name" placeholder="Nome del negozio" value="<?= $seller->name ?>" maxlength="50" required>

				<p class="lbl">Descrizione (opzionale):</p>
				<textarea class="textarea" name="desc" placeholder="Descrizione del negozio"><?= $seller->description ?></textarea>
			</div>

			<div class="">
				<p class="lbl inlb">Ordina prodotti</p>

				<span class="mt-2 inlb">
					<?php info_box('Puoi scegliere se ordinare i prodotti dal più poplare o dal più recente al meno, oppure seleziona "ordine personalizzato" per seguire l\'ordine della tua lista dei lavori. Questo campo influenza solo l\'ordine predefinito, i clienti potranno comunque riordinare i prodotti in ordine di popolarità o di data di creazione.') ?>
				</span>

				<p class="lbl inlb">:</p><br>

				<select class="sel" name="sort">
					<option value="1" <?php if ($seller->sort == 1) echo 'selected' ?>>Dal più venduto al meno</option>
					<option value="2" <?php if ($seller->sort == 2) echo 'selected' ?>>Dal più recente al meno</option>
					<option value="3" <?php if ($seller->sort == 3) echo 'selected' ?>>Ordine personalizzato</option>
				</select>
			</div>
		</div>

		<div class="form-foot">
			<button type="submit" name="edit" class="btn">Modifica</button>
		</div>
	</form>

	<form class="form" id="socials" method="POST">
		<h1 class="stitle br-c1">Socials & Contatti</h1>

		<?php if ($saved == 'socials') { ?>
			<p class="confirmation mar-t-1">Modifiche salvate</p>
		<?php } ?>

		<div class="grid gap1 mar-t-1">
			<p class="lbl">Website:</p>
			<input type="text" class="input" name="site" value="<?= $row_link['site'] ?? '' ?>" placeholder="URL website">
			<p class="lbl">Facebook:</p>
			<input type="text" class="input" name="fb" value="<?= $row_link['facebook'] ?? '' ?>" placeholder="Username Facebook o URL completo">
			<p class="lbl">Instagram:</p>
			<input type="text" class="input" name="inst" value="<?= $row_link['instagram'] ?? '' ?>" placeholder="Username Instagram o URL completo">
			<p class="lbl">Twitter:</p>
			<input type="text" class="input" name="twit" value="<?= $row_link['twitter'] ?? '' ?>" placeholder="Username Twitter o URL completo">
			<p class="lbl">YouTube:</p>
			<input type="text" class="input" name="yt" value="<?= $row_link['youtube'] ?? '' ?>" placeholder="Username YouTube o URL completo">
		</div>

		<div class="form-foot">
			<button type="submit" name="edit-socials" class="btn">Modifica</button>
		</div>
	</form>
</div>

<div class="form">

	<h1 class="stitle br-c1">Immagini negozio</h1>

	<!-- Profile icon -->
	<form class="file-form text-center" method="POST" enctype="multipart/form-data">

		<p class="txt-12rem mar-v-2">Immagine di profilo:</p>

		<div id="edit-icon" class="mar-h-auto">
			<div class="bgr" <?= temp_bg($seller->icon) ?>></div>
		</div>

		<p class="mar-t-1 img-txt mar-h-auto">L'immagine di profilo deve avere il lato minore di almeno <strong>150px x 150px</strong>. Questa immagine appare nella pagina del tuo negozio e nelle pagine dei tuoi articoli.</p>

		<button type="button" class="btn btn--secondary button--auto-width place-center file-cancel mar-t-1" onclick="$(this).parent().find('.file-inp').click();">
			<?= embed_icon('upload') ?>
			Cambia la foto
		</button>

		<div class="file-load"></div>

		<input type="file" name="file" class="hidden file-inp">
		<button type="submit" name="edit-icon" class="hidden file-submit"></button>
	</form>

	<div class="line br-c1 mar-t-2"></div>

	<!-- Cover image -->
	<?php $no_cover = strpos($seller->cover[0], '0.jpg') ?>

	<form class="file-form text-center pad-b-2" method="POST" enctype="multipart/form-data">

		<p class="txt-12rem mar-v-2">Immagine di copertina:</p>

		<?php if (!$no_cover) { ?>

			<div id="edit-cover" class="bgr-c1">
				<div id="edit-cover-img" class="bgr" <?= temp_bg($seller->cover(1024)) ?>></div>
				<div class="loading-icon bgr"></div>
			</div>

		<?php } ?>

		<p class="mar-t-1 img-txt mar-h-auto">L'immagine di copertina deve avere una dimensione di almeno <strong>1600px x 600px</strong>. Questa immagine è usata nella parte alta della pagina del tuo negozio.</p>

		<button type="button" class="btn btn--secondary button--auto-width place-center file-cancel mar-t-1" onclick="$(this).parent().find('.file-inp').click();">
			<?= embed_icon('upload') ?>
			<?= $no_cover ? 'Carica copertina' : 'Cambia copertina' ?>
		</button>

		<div class="file-load"></div>

		<input type="file" name="file" class="hidden file-inp">
		<button type="submit" name="edit-cover" class="hidden file-submit"></button>
	</form>
</div>

{% end content %}