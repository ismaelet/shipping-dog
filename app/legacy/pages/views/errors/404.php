{% block main %}

<div class="page-content grid-flow stack-content center-content">
	<div class="page-background fill-all background-fill" <?= background_image('/pages/assets/errors/404/background.webp') ?>></div>

	<div class="page-body grid-flow center-content" data-spacing="400">
		<h1 class="title">Errore 404</h1>
		<p class="section-subtitle">Pagina non trovata</p>
		<a class="btn btn--secondary button--auto-width" href="<?= url()->previous() ?>">Torna alla pagina precedente</a>
	</div>
</div>

{% end main %}