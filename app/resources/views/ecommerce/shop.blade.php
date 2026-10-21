<x-layouts.main
	:$location
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
					class="text-[min(7rem,20vw)] font-normal tracking-[0.3em] text-[#202020] [text-shadow:0.07em_0.04em_0_var(--color-primary-400)]"
				>
					SHO<span class="tracking-normal">P</span>
				</h1>

				<!-- Search box -->
				<form
					class="page-search"
					method="GET"
					autocomplete="off"
				>
					<input
						type="search"
						name="search"
						class="page-search__input text-neutral-light-700 bg-white"
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
			<x-heading.component>
				<span class="txt-light">I nostri</span> <span class="txt-c1 font-semibold">prodotti</span>
			</x-heading.component>
		</div>

		<livewire:shop.product-list />

	</div>
</x-layouts.main>
