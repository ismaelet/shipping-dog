@props(['variant', 'level' => null])

@php
	$allowedVariants = ['main', 'section', 'section-small', 'article', 'article-small'];

	$defaultLevel = [
	    'main' => 'h1',
	    'section' => 'h2',
	    'section-small' => 'h2',
	    'article' => 'h3',
	    'article-small' => 'h4',
	];

	$variant = in_array($variant, $allowedVariants) ? $variant : 'section';

	$level = $level ?? $defaultLevel[$variant];
	$level = str_contains($level, 'h') ? $level : 'h' . $level;
@endphp

@pushOnce('styles')
	<style>
		@layer utilities {
			:where(.heading) {
				color: var(--color-heading-400);
			}
		}

		.heading {
			--font-progress-max: 90rem;
			--font-progress-min: 20rem;
			--font-progress: progress(100vw, var(--font-progress-min), var(--font-progress-max));
			--font-size: calc(var(--font-size-min) + var(--font-progress) * (var(--font-size-max) - var(--font-size-min)));
			font-family: var(--font-heading);
		}

		.heading[data-variant="main"] {
			--font-size-max: 4rem;
			--font-size-min: 3rem;
			font-size: var(--font-size);
			font-weight: var(--font-weight-semibold);
			line-height: 1;
		}

		.heading[data-variant="section"] {
			--font-size-max: 3rem;
			--font-size-min: 2.5rem;
			font-size: var(--font-size);
			font-weight: var(--font-weight-semibold);
			line-height: 1;
		}

		.heading[data-variant="section-small"] {
			--font-size-max: 2.25rem;
			--font-size-min: 1.875rem;
			font-size: var(--font-size);
			font-weight: var(--font-weight-semibold);
			line-height: 1;
		}

		.heading[data-variant="article"] {
			font-size: var(--text-2xl);
			font-weight: var(--font-weight-semibold);
		}

		.heading[data-variant="article-small"] {
			font-size: var(--text-lg);
			font-weight: var(--font-weight-semibold);
		}
	</style>
@endpushOnce

<{{ $level }}
	{{ $attributes->merge(['class' => 'heading']) }}
	data-variant="{{ $variant }}"
>
	{{ $slot }}
	</{{ $level }}>
