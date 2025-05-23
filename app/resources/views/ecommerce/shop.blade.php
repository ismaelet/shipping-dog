@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/shop.css') }}"
	>
	<style>
		main {
			--breakout-width: 1600px;
		}
	</style>
@endpushOnce

<x-layouts.legacy-main
	:$locationTag
	:$title
>

	<section
		id="slider"
		class="span-full-width"
	>
		<div
			id="slider-box"
			class="relative"
		>
			<div
				id="bgr"
				class="cover-background"
				<?= background_image('shop/background.jpg') ?>
			></div>

			<div class="relative grid justify-items-center gap-8 px-4 text-center">
				<h1
					id="title"
					class="txt-wht"
				>SHO<span style="letter-spacing: 0;">P</span></h1>

				<!-- Search box -->
				<form
					class="page-search"
					method="GET"
					autocomplete="off"
				>
					<input
						type="search"
						name="search"
						class="page-search__input"
						value="<?= $_GET['search'] ?? '' ?>"
						placeholder="Cerca un prodotto"
						maxlength="30"
						onfocus="$(this).next().addClass('focus');"
						onblur="$(this).next().removeClass('focus');"
						autocomplete="off"
					>

					<x-button
						type="submit"
						class="page-search__button btn"
					>
						{!! embed_icon('search') !!}
					</x-button>
				</form>
			</div>
		</div>

		<div
			id="cred"
			class="relative"
		>
			<img
				id="cred-ico"
				src="<?= $highlightedDesign->seller->icon() ?>"
			/>
			<p id="cred-txt">
				<a href="<?= $highlightedDesign->url() ?>"><?= $highlightedDesign->name ?></a> by <a
					class="txt-c1"
					href="<?= $highlightedDesign->seller->url() ?>"
				><?= $highlightedDesign->seller->name ?></a>
			</p>
		</div>
	</section>


	<div
		id="body"
		class="breakout"
	>

		<div class="stitle-box mt-8">
			<h2 class="component-heading"><span class="txt-light">I NOSTRI</span> <span
					class="txt-c1 font-semibold">PRODOTTI</span></h2>
			<div
				id="anch1"
				class="anchor"
			></div>
		</div>

		<x-shop.product-list />

	</div>

</x-layouts.legacy-main>
