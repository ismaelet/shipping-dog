@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/home-categories.css') }}"
	>
@endpushOnce

<section id="categories">

	<!-- Features -->
	<section
		id="features"
		class="features"
	>
		<div class="content-grid">
			<div class="grid gap-8 md:grid-cols-3">

				@foreach ($features as $key => $feature)
					<div class="feature va-cen grid">
						<div class="icon-box">
							<?= embed_icon('home_feature_' . ($key + 1)) ?>
						</div>
						<div class="body">
							<p class="title"><?= $feature[0] ?></p>
							<p class="text"><?= $feature[1] ?></p>
						</div>
					</div>
				@endforeach

			</div>
		</div>
	</section>

	<div class="content-grid mob480">
		<h2 class="section-heading">I nostri prodotti</h2>
	</div>

	<section class="featured-categories grid">

		<!-- Categories -->
		<?php foreach ($categories as $key => $category) { ?>

		<a
			class="featured-category"
			href="<?= client_url('/' . $category[2]) ?>"
		>
			<div class="img-box">
				<img
					class="img-fill"
					src="<?= Vite::image('home/category-' . ($key + 1) . '-1.jpg') ?>"
					alt=""
					loading="lazy"
				>
				<img
					class="img-fill"
					src="<?= Vite::image('home/category-' . ($key + 1) . '-2.jpg') ?>"
					alt=""
					loading="lazy"
				>
				<div class="content desk480">
					<p class="text"><?= $category[1] ?></p>
					<h3 class="name"><?= $category[0] ?></h3>
					<x-button
						variant="secondary"
						class="mt-4 cursor-pointer"
					>Esplora</x-button>
				</div>
			</div>
			<p class="name-mob txt-line mob480"><?= str_replace('<br>', ' ', $category[0]) ?></p>
		</a>

		<?php } ?>

	</section>
</section>
