<div class="form-field md:col-span-2">
	<x-label>Tipo di prodotto:</x-label>

	<select
		class="select self-end"
		name="type_id"
		wire:model.live="typeId"
		x-model.number="typeId"
		x-on:change="changeType()"
		required
	>
		@foreach ($types as $type)
			<option value="{{ $type->id }}">
				{{ $type->name }}
			</option>
		@endforeach
	</select>
</div>
