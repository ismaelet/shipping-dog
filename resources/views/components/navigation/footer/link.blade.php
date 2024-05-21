@if ($href)
	<p class="font-heading flex gap-3 font-light">
		<a
			href="{{ $href }}"
			class="hover:text-primary contents transition-colors"
		>
			@if (isset($icon))
				<span class="embedded-svg text-primary flex h-5 w-5 justify-center">
					{{ embed_icon('icons/contacts/' . $icon) }}
				</span>
			@endif

			{{ $slot }}
		</a>
	</p>
@else
	<p class="font-heading font-light">
		{{ $slot }}
	</p>
@endif
