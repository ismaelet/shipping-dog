<div>
	@auth
		<button
			type="button"
			@class([
				'like-button group grid size-10 cursor-pointer place-items-center rounded-full backdrop-blur transition-colors',
				'bg-primary-400 hover:bg-primary-500 text-white' => $wishlisted,
				'bg-white/90 text-slate-900 hover:bg-white' => !$wishlisted,
			])
			title="{{ $wishlisted ? 'Rimuovi dai preferiti' : 'Aggiungi ai preferiti' }}"
			aria-label="{{ $wishlisted ? 'Rimuovi dai preferiti' : 'Aggiungi ai preferiti' }}"
			aria-pressed="{{ $wishlisted ? 'true' : 'false' }}"
			data-wishlisted="{{ $wishlisted ? 'true' : 'false' }}"
			wire:click="{{ $wishlisted ? 'destroy' : 'store' }}"
			wire:loading.attr="disabled"
			wire:target="store,destroy"
		>
			<x-dynamic-component
				:component="$wishlisted ? 'icon-heart-filled' : 'icon-heart'"
				class="size-4"
			/>
		</button>
	@else
		<a
			href="{{ route('login', ['url' => request()->url()]) }}"
			class="like-button grid size-10 cursor-pointer place-items-center rounded-full bg-white/90 text-slate-900 backdrop-blur transition-colors hover:bg-white"
			title="Aggiungi ai preferiti"
			aria-label="Aggiungi ai preferiti"
			data-wishlisted="false"
		>
			<x-icon-heart class="size-4" />
		</a>
	@endauth
</div>
