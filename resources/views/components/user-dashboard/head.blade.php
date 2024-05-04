@php
	$user = auth()->user();
@endphp

@pushOnce('styles')
	<style>
	</style>
@endpushOnce

<section class="full-width">
	<div
		class="full-width h-72 bg-cover bg-center"
		style="background-image: url({{ $user->cover() }})"
	></div>

	<div class="grid grid-cols-1 justify-items-center gap-1">

		<img
			src="{{ public_asset($user->icon()) }}"
			class="border-background -mt-10 h-20 w-20 rounded-full border-[5px]"
		>

		<x-typography.component-heading
			class="break-all text-center">{{ !$user->username ?: $user->email }}</x-typography.component-heading>

		<p class="">
			<?= $user->first_name ?>
			<?= empty($user->last_name) ? '' : ' ' . $user->last_name ?>
		</p>

	</div>
</section>
