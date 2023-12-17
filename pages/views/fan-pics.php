<?php

use Ecommerce\Design;

?>

{% block main %}

<section id="client-pics">
	<div class="header">
		<div class="main-wrap txt-cen">
			<div class="section-title-box">
				<h1 class="section-title txt-sbold">Le vostre foto</h1>
			</div>

			<p class="mwid-600 mar-h-auto mar-t-2 txt-12rem">Mandaci una foto con un nostro capo indossato su <a class="txt-c1-h" href="<?= sys_config('contact-instagram-link') ?>">Instagram</a>, <a class="txt-c1-h" href="<?= sys_config('contact-facebook') ?>">Facebook</a> o per <a class="txt-c1-h" href="<?= sys_config('contact-email-link') ?>">e-email</a> e ottieni subito un <strong>codice sconto del 15%</strong></p>
		</div>
	</div>

	<div class="main-wrap">
		<div class="gallery">

			<?php while ($row = $client_pics->fetch_assoc()) {
				$design = new Design($row['desID']);

				$lname = empty($row['lname']) || $row['lname'] == '-' ? '' : substr($row['lname'], 0, 1) . '.';

			?>

				<a href="<?= $design->url ?>" class="image stack-items">
					<img src="<?= client_path('/uploads/img_fans/' . $row['ID'] . '.jpg') ?>" alt="" loading="lazy" class="img-fill">
					<div class="overlay pad-2 grid va-cen txt-cen">
						<div class="title-box">
							<p class="design-name txt-wht txt-11rem txt-bold txt-line"><?= $design->name ?></p>
							<p class="client-name txt-wht txt-upp txt-line"><?= $row['fname'] . ' ' . $lname ?></p>
						</div>
					</div>
				</a>

			<?php } ?>

		</div>
	</div>

</section>

{% end main %}