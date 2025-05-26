<x-layouts.admin-dashboard>

	<div class="grid gap-8">
		{{-- Title bar --}}
		<x-dashboard.table.title :$title />

		@session('status')
			{{-- Status alert --}}
			<x-alerts.success>{{ $value }}</x-alerts.success>
		@endsession

		@session('error')
			{{-- Error alert --}}
			<x-alerts.error>{{ $value }}</x-alerts.error>
		@endsession

		{{-- Table --}}
		<x-table
			:$columns
			:$records
			:$recordCount
			:$mainOrderBy
			:$buttons
		/>
	</div>

</x-layouts.admin-dashboard>
