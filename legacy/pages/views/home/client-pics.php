<section id="client-pics">
	<div class="header">
		<div class="main-wrap txt-cen">
			<div class="grid">
				<div class="section-heading-box">
					<h2 class="section-heading">Le vostre foto</h2>
				</div>

				<p class="mwid-600 mar-h-auto">Mandaci una foto con un nostro capo indossato su <a class="txt-c1-h" href="<?= sys_config('contact-instagram-link') ?>">Instagram</a>, <a class="txt-c1-h" href="<?= sys_config('contact-facebook') ?>">Facebook</a> o per <a class="txt-c1-h" href="<?= sys_config('contact-email-link') ?>">e-email</a> e ottieni subito un <strong>codice sconto del 15%</strong></p>

				<div class="flex center-content">
					<a href="<?= sys_config('contact-instagram-link') ?>" class="btn button--auto-width mar-t-2">
						<?= embed_icon('contacts/instagram') ?>
						Seguici su Instagram
					</a>
				</div>

				<a href="<?= client_url('/fan-pics') ?>" class="block mar-t-3">
					<p class="more txt-rig">Esplorale tutte</p>
				</a>
			</div>
		</div>
	</div>

	<div class="main-wrap">
		<div class="gallery">

			<?php foreach ($customerPhotos as $customerPhoto) { ?>

				<a href="<?= $customerPhoto->design?->url() ?>" class="client__image image stack-items">
					<img src="<?= $customerPhoto->image() ?>" alt="" loading="lazy" class="img-fill">
					<div class="overlay pad-2 grid va-cen txt-cen">
						<div class="title-box">

							<?php if ($customerPhoto->design) { ?>
								<p class="design-name txt-wht txt-11rem font-semibold txt-line"><?= $customerPhoto->design->name ?></p>
							<?php } ?>

							<p class="client-name txt-wht txt-upp txt-line"><?= $customerPhoto->fullName() ?></p>
						</div>
					</div>
				</a>

			<?php } ?>

		</div>
	</div>
</section>