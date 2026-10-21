<div
	class="inline-grid grid-cols-3 gap-0.5 rounded-full bg-slate-800 p-1 text-slate-300"
	role="group"
	aria-label="Seleziona tema"
	data-theme-switcher
>
	@foreach (['system', 'light', 'dark'] as $theme)
		<label class="cursor-pointer rounded-full">
			<input
				type="radio"
				name="theme"
				value="{{ $theme }}"
				class="peer sr-only"
				aria-label="{{ match ($theme) {
				    'system' => 'Tema di sistema',
				    'light' => 'Tema chiaro',
				    'dark' => 'Tema scuro',
				} }}"
			>

			<span
				class="grid size-9 place-items-center rounded-full transition-colors hover:text-white peer-checked:bg-white peer-checked:text-slate-950 peer-checked:hover:text-slate-950 peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-orange-500"
			>
				@switch($theme)
					@case('system')
						<svg
							class="size-5"
							viewBox="0 0 24 24"
							fill="none"
							stroke="currentColor"
							stroke-width="1.5"
							aria-hidden="true"
						>
							<rect
								x="3"
								y="4"
								width="18"
								height="13"
								rx="2"
							/>
							<path
								d="M8 21h8M12 17v4"
								stroke-linecap="round"
							/>
						</svg>
					@break

					@case('light')
						<svg
							class="size-5"
							viewBox="0 0 24 24"
							fill="none"
							stroke="currentColor"
							stroke-width="1.5"
							stroke-linecap="round"
							aria-hidden="true"
						>
							<circle
								cx="12"
								cy="12"
								r="4"
							/>
							<path
								d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42"
							/>
						</svg>
					@break

					@case('dark')
						<svg
							class="size-5"
							viewBox="0 0 24 24"
							fill="none"
							stroke="currentColor"
							stroke-width="1.5"
							stroke-linejoin="round"
							aria-hidden="true"
						>
							<path d="M20.5 14.5A8.5 8.5 0 0 1 9.5 3.5 8.5 8.5 0 1 0 20.5 14.5Z" />
						</svg>
					@break
				@endswitch
			</span>
		</label>
	@endforeach
</div>
