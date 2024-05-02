<?php

use Legacy\Classes\Ecommerce\Design as LegacyDesign;

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

			<?php while ($row = $client_pics->fetch_assoc()) {
				$design = new Design($row['design_id']);

				$last_name = empty($row['last_name']) || $row['last_name'] == '-' ? '' : substr($row['last_name'], 0, 1) . '.';

			?>

				<a <?= $design->id ? 'href="' . $design->url . '"' : '' ?> class="image stack-items">
					<img src="<?= public_asset('users/testimonials/' . $row['id'] . '.jpg') ?>" alt="" loading="lazy" class="img-fill">
					<div class="overlay pad-2 grid va-cen txt-cen">
						<div class="title-box">
							<p class="design-name txt-wht txt-11rem txt-bold txt-line"><?= $design->name ?></p>
							<p class="client-name txt-wht txt-upp txt-line"><?= $row['first_name'] . ' ' . $last_name ?></p>
						</div>
					</div>
				</a>

			<?php } ?>

		</div>
	</div>

</section>

{% end main %}