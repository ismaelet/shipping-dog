{% block content %}

<h1 class="form-title">Aggiungi una foto cliente</h1>

<form class="form file-form" method="POST" enctype="multipart/form-data" autocomplete="off">

	<div class="body pad-t-2">

		<p class="mar-b-2 txt-sold">
			<span class="btn-icon inlb icon bgr-c2"><?= embed_icon('info') ?></span>
			<span class="btn-txt">Se un cliente ti invia una foto con uno dei tuoi prodotti indossati puoi caricarla qui, apparirà nella pagina del prodotto e nella homepage tra le foto dei clienti.</span>
		</p>

		<?php generate_form($inputs); ?>
	</div>

	<div class="form-foot">
		<button type="submit" name="submit" class="pbtn file-submit">Aggiungi</button>
		<div class="file-load"></div>
	</div>

</form>

{% end content %}