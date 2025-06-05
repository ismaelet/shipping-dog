@pushOnce('styles')
	<style>
		.loading-spinner {
			position: relative;
			display: flex;
			align-items: center;
			justify-content: center;
			width: 100%;
			max-width: 6rem;
			margin-top: 3rem;
			margin-bottom: 3rem;
		}

		.loading-spinner:before,
		.loading-spinner:after {
			content: "";
			position: absolute;
			border-radius: 50%;
			animation: pulsOut 1.8s ease-in-out infinite;
			filter: drop-shadow(0 0 1rem color-mix(in hsl, var(--color-primary-400) 75%, transparent));
		}

		.loading-spinner:before {
			width: 100%;
			padding-bottom: 100%;
			box-shadow: inset 0 0 0 1rem var(--color-primary-400);
			animation-name: pulsIn;
		}

		.loading-spinner:after {
			width: calc(100% - 2rem);
			padding-bottom: calc(100% - 2rem);
			box-shadow: 0 0 0 0 var(--color-primary-400);
		}

		@keyframes pulsIn {
			0% {
				box-shadow: inset 0 0 0 1rem var(--color-primary-400);
				;
				opacity: 1;
			}

			50%,
			100% {
				box-shadow: inset 0 0 0 0 var(--color-primary-400);
				opacity: 0;
			}
		}

		@keyframes pulsOut {

			0%,
			50% {
				box-shadow: 0 0 0 0 var(--color-primary-400);
				opacity: 0;
			}

			100% {
				box-shadow: 0 0 0 1rem var(--color-primary-400);
				opacity: 1;
			}
		}
	</style>
@endpushOnce

<span {{ $attributes->class('loading-spinner') }}></span>
