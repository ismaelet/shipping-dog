@switch($column['type'] ?? 'default')
	@case('int')
		{{ $record->{$column['name']} ?: '0' }}
	@break

	@case('float')
		{{ $record->{$column['name']} ?: '0.00' }}
	@break

	@case('price')
		<span class="whitespace-nowrap">
			{{ \App\Helpers\EcommerceHelper::formatPrice($record->{$column['name']}, true) }}
		</span>
	@break

	@case('date')
		{{ $record->{$column['name']} ? $record->{$column['name']}->format('d/m/Y') : '-' }}
	@break

	@case('bool')
		<span class="h-4">
			<x-dynamic-component :component="'icon-' . ($record->{$column['name']} ? 'correct' : 'wrong')" />
		</span>
	@break

	@case('checkbox')
		<x-checkbox
			name="{{ $column['name'] }}[]"
			checked="{{ !empty($column['checked']) }}"
			:value="$record->{$column['name']}"
		/>
	@break

	@case('method')
		@php
			$methods = explode('.', $column['method']);
			$value = $record;

			foreach ($methods as $method) {
			    if ($method == end($methods)) {
			        $value = isset($value) ? $value->{$method}() : null;
			    } else {
			        $value = $value->{$method};
			    }
			}
		@endphp

		<span @class([
			'whitespace-nowrap' => isset($column['wrap']) && !$column['wrap'],
		])>
			{!! $value !!}
		</span>
	@break

	@default
		<span @class([
			'whitespace-nowrap' => isset($column['wrap']) && !$column['wrap'],
		])>
			{!! $record->{$column['name']} ?: '-' !!}
		</span>
@endswitch
