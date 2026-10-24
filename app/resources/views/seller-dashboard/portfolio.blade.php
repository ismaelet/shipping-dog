<x-layouts.seller-dashboard>
	<div class="grid grid-cols-1 gap-6">
		<nav
			aria-label="Sezioni portfolio"
			class="border-neutral-border bg-surface-400 flex w-fit items-center rounded-full border p-1"
		>
			<a
				href="{{ route('seller-dashboard.portfolio') }}"
				@class([
					'rounded-full px-5 py-2 text-sm font-bold transition-colors',
					'bg-primary-400 text-white' => !$activeTab,
					'text-neutral-text hover:text-neutral-heading' => $activeTab,
				])
				@if (!$activeTab) aria-current="page" @endif
			>
				Portfolio
			</a>

			<a
				href="{{ route('seller-dashboard.portfolio.add') }}"
				@class([
					'rounded-full px-5 py-2 text-sm font-bold transition-colors',
					'bg-primary-400 text-white' => $activeTab,
					'text-neutral-text hover:text-neutral-heading' => !$activeTab,
				])
				@if ($activeTab) aria-current="page" @endif
			>
				Carica una grafica
			</a>
		</nav>

		@unless ($activeTab)
			<livewire:seller.activation-request :$seller />
		@endunless

		@if ($activeTab)
			@include('seller-dashboard.portfolio._upload')
		@else
			<livewire:seller.portfolio-sorter />
		@endif
	</div>
</x-layouts.seller-dashboard>
