<?php

use Legacy\View\Components\Interactions\FollowButton;

?>

@pushOnce('styles')
	<style>
#art-sec { background-color: var(--color-neutral-200); }
		#art-sec .follow-btn { background-color: var(--color-surface-500) !important; color: var(--text-color); }
		#art-tbl { display: table; margin: 0 auto; }
		#art-ico-cell { display: table-cell; width: 4rem; padding: 10px 0; vertical-align: middle; }
		#art-ico { display: block; background-position: center; background-size: cover; }
		#art-ico img { background-color: var(--color-surface-500); border-radius: 100%; }
		#art-name-cell { display: table-cell; padding: 1rem 0 1rem 1rem; vertical-align: middle; }
		#art-name { font-size: 1rem; letter-spacing: 0.08em; }
		#art-date { padding: 4px 0; font-size: 10px; letter-spacing: 0.1em; text-transform: uppercase; }
		#more-sec { min-height: 5rem; }
		#more-gall { scroll-snap-type: inline mandatory; overflow-x: auto; }
		.more-title { text-align: center; font-size: 12px; letter-spacing: 0.5em; text-transform: uppercase; }
		.more-line { display: inline-block; background-color: var(--color-primary-400); width: 40px; max-width: 8%; height: 2px; margin: 1rem 0; }
		.more-box { padding: 0.5rem; }
		#more-gall-box img { background-color: #ebebeb; border-radius: 0.5rem; aspect-ratio: 1; scroll-snap-align: start; }
		@media (max-width: 768px) { #more-gall { grid-auto-flow: column; grid-template-columns: initial; grid-auto-columns: 40vw; } }
	</style>
@endpushOnce

<!-- Artist -->
<section
	id="art-sec"
	class="full-width"
>
	<div class="">
		<div id="art-tbl">
			<div id="art-ico-cell">
				<a
					id="art-ico"
					href="{{ $product->seller->url() }}"
				>
					<img
						src="{{ $product->seller->icon() }}"
						alt=""
					>
				</a>
			</div>

			<div id="art-name-cell">
				<h1
					id="art-name"
					class="font-semibold"
				><a href="{{ $product->seller->url() }}">{{ $product->seller->name }}</a></h1>
				<p id="art-date">membro dal {{ $product->seller->createdAtString() }}</p>

				<!-- Follow artist -->
				<livewire:follow-button :seller="$product->seller" />
			</div>
		</div>
	</div>
</section>

<!-- More items -->
<section
	id="more-sec"
	class=""
>
	@if ($product->relatedProducts()->count())
		<div class="main-wrap pt-12 pb-16 text-center">
			<p class="more-title">Altro di questo negozio</p>

			<div class="more-line"></div>

			<div
				id="more-gall-box"
				class="mt-4"
			>
				<div
					id="more-gall"
					class="grid grid-cols-[repeat(auto-fill,minmax(12rem,1fr))] gap-4"
				>

					@foreach ($product->relatedProducts() as $relatedProduct)
						<a href="{{ $relatedProduct->url() }}">
							<img
								src="{{ $relatedProduct->image() }}"
								class="img-fill"
							>
						</a>
					@endforeach

				</div>
			</div>
		</div>
	@endif

</section>
