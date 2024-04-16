{% block content %}

<div class="list <?php if (!$selN) echo 'empty' ?>">

	<div class="list-head">
		<h1 class="list-title bgr-c1 txt-wht">Lista foto utenti</h1>
	</div>

	<a class="block pad-h-2 pad-t-2 txt-cen" href="<?= client_url('/manage/pics/add') ?>">

		<button type="button" class="btn btn--secondary button--auto-width br-c1 mx-auto ">
			<?= embed_icon('upload') ?>
			Carica foto cliente
		</button>

	</a>

	<div class="list-body">

		<?php if ($selN) { ?>

			<div class="list-cont">
				<div class="list-tbl">

					<div class="list-tbl-head">
						<p class="list-name list-col-med">Nome</p>
						<p class="list-name list-col-med">Cognome</p>
						<p class="list-name list-col-lon">Design</p>
						<p class="list-name list-col-shr">Capo</p>
						<p class="list-name list-col-med">Data</p>
						<p class="list-name list-col-btn"></p>
					</div>


					<?php $i = 1;
					while ($row = $res_sel->fetch_assoc()) {
						$i++;

						$imgID = $row['id'];

						$des_name = DBOld::find('name FROM designs WHERE id = ?', [$row['design_id']]);
						$type_name = DBOld::find('name FROM items_types WHERE tag = ?', [$row['type']]) ?>

						<form class="list-row <?php if ($i % 2) echo 'dark' ?>" method="POST">
							<input type="hidden" name="imgID" value="<?= $imgID ?>">
							<div class="list-cell">
								<p class="list-inp"><?= $row['first_name'] ?></p>
							</div>
							<div class="list-cell">
								<p class="list-inp"><?= $row['last_name'] ?></p>
							</div>
							<div class="list-cell">
								<p class="list-inp"><?= $des_name ?></p>
							</div>
							<div class="list-cell">
								<p class="list-inp"><?= $type_name ?></p>
							</div>
							<div class="list-cell">
								<p class="list-inp"><?= $row['time'] ?></p>
							</div>

							<div class="list-cell">
								<a href="<?= CLIENT_ROOT ?>/uploads/users/testimonials/<?= $imgID ?>.jpg">
									<button type="button" class="list-btn gre">Visualizza</button>
								</a>

								<button type="submit" name="delete" class="list-btn red" onclick="return confirm('Sei sicuro di voler eliminare l\'immagine?')">Elimina</button>
							</div>
						</form>
					<?php } ?>
				</div>
			</div>

		<?php } else { ?>

			<p class="mar-b-2 txt-c-title txt-med txt-cen">

				<span class="btn-icon inlb icon"><?= embed_icon('info') ?></span>

				<span class="btn-txt">Se un cliente ti invia una foto con uno dei tuoi prodotti indossati puoi caricarla qui, apparirà nella pagina del prodotto e nella homepage tra le foto dei clienti.</span>

			</p>

		<?php } ?>

	</div>
</div>

{% end content %}