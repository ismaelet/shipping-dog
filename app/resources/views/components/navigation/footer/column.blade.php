<div class="grid content-start gap-4">
	<h3 class="font-heading border-secondary border-l-[4px] pl-2 text-lg font-semibold uppercase tracking-widest">
		{{ $title }}</h3>

	@foreach ($data as $link)
		@if (isset($link['contacts']))
			<x-navigation.footer.link
				:href="config('custom.contacts.' . $link['contacts'] . '.url')"
				:icon="isset($link['icon']) ? $link['contacts'] : null"
			>
				{{ config('custom.contacts.' . $link['contacts'] . '.label') }}
			</x-navigation.footer.link>
		@else
			<x-navigation.footer.link :href="isset($link['url']) ? route($link['url']) : null">
				{{ $link['label'] }}
			</x-navigation.footer.link>
		@endif
	@endforeach
</div>
