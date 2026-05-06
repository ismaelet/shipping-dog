<div>
	@auth
		<button
			type="button"
			class="like-button text-neutral-light-400 stack-content hover:text-primary group absolute right-2 top-2 grid h-7 w-7 cursor-pointer place-items-center rounded-full bg-white shadow transition-colors"
			title="{{ $wishlisted ? 'Rimuovi dai preferiti' : 'Aggiungi ai preferiti' }}"
			data-wishlisted="{{ $wishlisted ? 'true' : 'false' }}"
			wire:click="{{ $wishlisted ? 'destroy' : 'store' }}"
		>
			<x-dynamic-component
				:component="$wishlisted ? 'icon-love' : 'icon-love-stroke'"
				class="h-1/2 w-1/2 transition-opacity group-hover:opacity-0"
			/>

			<x-dynamic-component
				:component="$wishlisted ? 'icon-love-stroke' : 'icon-love'"
				class="h-1/2 w-1/2 opacity-0 transition-opacity group-hover:opacity-100"
			/>
		</button>
	@else
		<a
			class="like-button text-neutral-light-400 stack-content hover:text-primary group absolute right-2 top-2 grid h-7 w-7 cursor-pointer place-items-center rounded-full bg-white shadow transition-colors"
			href="{{ route('login', ['url' => request()->url()]) }}"
			data-wishlisted="false"
			title="Aggiungi ai preferiti"
		>
			<x-dynamic-component
				component="icon-love-stroke"
				class="h-1/2 w-1/2 transition-opacity group-hover:opacity-0"
			/>

			<x-dynamic-component
				component="icon-love"
				class="h-1/2 w-1/2 opacity-0 transition-opacity group-hover:opacity-100"
			/>
		</a>
	@endauth
</div>
