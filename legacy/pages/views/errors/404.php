{% block main %}

<div class="page-content grid-flow stack-content center-content">
	<div class="page-background fill-all background-fill" <?= background_image('background') ?>></div>

	<div class="page-body grid-flow center-content" data-spacing="400">
		<h1 class="title"><?= ('Error 404') ?></h1>
		<p class="section-subtitle"><?= ('Page not found') ?></p>
		<a class="btn btn--secondary button--auto-width" href="<?= client_url('/') ?>"><?= ('Back to home') ?></a>
	</div>
</div>

{% end main %}