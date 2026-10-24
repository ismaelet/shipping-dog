@props(['variant', 'level' => null])

@php
	$allowedVariants = ['main', 'section', 'article', 'article-small'];
	$defaultLevel = ['main' => 'h1', 'section' => 'h2', 'article' => 'h3', 'article-small' => 'h4'];

	$variant = in_array($variant, $allowedVariants) ? $variant : 'section';

	$level = $level ?? $defaultLevel[$variant];
	$level = str_contains($level, 'h') ? $level : 'h' . $level;
@endphp

@pushOnce('styles')
	<style>
		@layer utilities {
			:where(.heading-main, .heading-section, .heading-article, .heading-article-small) {
				color: var(--color-neutral-heading);
			}
		}

		.heading-main,
		.heading-section,
		.heading-article,
		.heading-article-small {
			font-family: var(--font-heading);
			text-box: trim-both cap alphabetic;
		}

		.heading-main {
			font-size: var(--text-4xl);
		}

		.heading-section {
			font-size: var(--text-3xl);
			line-height: 1;
		}

		.heading-article {
			font-size: var(--text-2xl);
			font-weight: var(--font-weight-semibold);
		}

		.heading-article-small {
			font-size: var(--text-lg);
			font-weight: var(--font-weight-semibold);
		}
	</style>
@endpushOnce

<{{ $level }} {{ $attributes->merge(['class' => 'heading-' . $variant]) }}>
	{{ $slot }}
	</{{ $level }}>
