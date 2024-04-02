<footer>
	<div id="footer-wrap" class="main-wrap">
		<div id="footer-body" class="">

			<?php foreach ($footer['columns'] as $f_column) { ?>

				<div class="footer-col">

					<h3 class="footer-title"><?= $f_column[0] ?></h3>

					<ul class="links">

						<?php foreach ($f_column[1] as $f_link) { ?>

							<li>

								<?php if (isset($f_link[1])) { ?>

									<p class="footer-link active">
										<a href="<?= $f_link[1] ?>"><?= $f_link[0] ?></a>
									</p>

								<?php } else { ?>

									<p class="footer-link"><?= $f_link[0] ?></p>

								<?php } ?>

							</li>

						<?php } ?>

					</ul>

				</div>

			<?php } ?>

			<?php if ($footer['contacts']) { ?>

				<div class="contacts footer-col">

					<h3 class="footer-title">Contatti</h3>

					<ul class="flex">

						<?php if (in_array('instagram', $footer['contacts'])) { ?>
							<a href="<?= sys_config('contact-instagram-link') ?>" class="footer-soc-icon bgr-c1-h center-items">
								<div class="icon-box">
									<?= icon_embed('contacts/instagram') ?>
								</div>
							</a>
						<?php } ?>

						<?php if (in_array('facebook', $footer['contacts'])) { ?>
							<a href="<?= sys_config('contact-facebook-link') ?>" class="footer-soc-icon bgr-c1-h center-items">
								<div class="icon-box">
									<?= icon_embed('contacts/facebook') ?>
								</div>
							</a>
						<?php } ?>

						<?php if (in_array('email', $footer['contacts'])) { ?>
							<a href="<?= sys_config('contact-email-link') ?>" class="footer-soc-icon bgr-c1-h center-items">
								<div class="icon-box">
									<?= icon_embed('contacts/email') ?>
								</div>
							</a>
						<?php } ?>

					</ul>

				</div>

			<?php } ?>

		</div>

		<p id="footer-notes">
			Copyright © <?= getdate()['year'] ?> <span class="txt-wht"><?= sys_config('name-full') ?></span>. Il copyright © di ogni design appartiene al rispettivo proprietario.
			<br>Sito web realizzato da <a class="txt-wht-h" href="emailto:ismaele.tuffanelli@gmail.com">Ismaele Tuffanelli</a>
		</p>
	</div>
</footer>