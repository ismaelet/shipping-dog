@pushOnce('scripts')
	<script defer>
		document.addEventListener('DOMContentLoaded', () => {
			const infos = document.querySelectorAll('.js-info');

			function update(tooltip) {
				if (!tooltip) return;

				// reset
				tooltip.style.setProperty('--tooltip-shift', '0px');

				// measure after reset
				const rect = tooltip.getBoundingClientRect();
				const padding = 12;

				let shift = 0;

				// right overflow
				if (rect.right > window.innerWidth - padding) {
					shift -= (rect.right - (window.innerWidth - padding));
				}

				// left overflow
				if (rect.left < padding) {
					shift += (padding - rect.left);
				}

				tooltip.style.setProperty('--tooltip-shift', `${Math.round(shift)}px`);
			}

			infos.forEach(info => {
				const tooltip = info.querySelector('[data-tooltip]');
				if (!tooltip) return;

				// update when it’s likely to matter
				info.addEventListener('mouseenter', () => requestAnimationFrame(() => update(tooltip)));
				window.addEventListener('resize', () => update(tooltip));
			});
		});
	</script>
@endpushOnce


<div class="info js-info group relative w-max cursor-pointer">
	<div class="group-hover:text-primary grid aspect-square w-6 place-items-center rounded-full border transition-colors">
		<x-icon-question class="w-2/5" />
	</div>

	<p
		class="info-text surface-2 bg-background w-120 pointer-events-none absolute left-1/2 z-10 max-w-[70vw] -translate-y-2 p-3 text-sm italic opacity-0 shadow transition-all group-hover:pointer-events-auto group-hover:translate-y-0 group-hover:opacity-100"
		style="transform: translateX(calc(-50% + var(--tooltip-shift, 0px))) translateY(-0.5rem);"
		data-tooltip
	>
		{{ $slot }}
	</p>
</div>
