{% block main %}

<div class="main-wrap self-align-center grid-flow center-content pad-v-5" data-spacing="700">
	<div class="empty-cart__icon">
		<?= icon_embed('empty-cart') ?>
	</div>

	<h1 class="section-title">Il tuo carrello è vuoto</h1>
	<p class="text--big">Puoi cercare dei prodotti da acquistare qui</p>
	<a href="<?= client_url('/shop') ?>" class="button">Catalogo</a>
</div>

{% end main %}