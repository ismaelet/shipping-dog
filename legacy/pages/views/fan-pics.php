<?php

?>

{% block main %}

<section id="client-pics">
	<div class="header">
		<div class="main-wrap txt-cen">
			<div class="section-heading-box">
				<h1 class="section-heading txt-sbold">Le vostre foto</h1>
			</div>

			<p class="mwid-600 mar-h-auto mar-t-2 txt-12rem">Mandaci una foto con un nostro capo indossato su <a class="txt-c1-h" href="<?= sys_config('contact-instagram-link') ?>">Instagram</a>, <a class="txt-c1-h" href="<?= sys_config('contact-facebook') ?>">Facebook</a> o per <a class="txt-c1-h" href="<?= sys_config('contact-email-link') ?>">e-email</a> e ottieni subito un <strong>codice sconto del 15%</strong></p>
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
								<p class="design-name txt-wht txt-11rem txt-bold txt-line"><?= $customerPhoto->design->name ?></p>
							<?php } ?>

							<p class="client-name txt-wht txt-upp txt-line"><?= $customerPhoto->fullName() ?></p>
						</div>
					</div>
				</a>

			<?php } ?>

		</div>
	</div>

</section>

{% end main %}