@props([
    'message' => $slot,
    'type' => 'error',
])

<p
	{{ $attributes->class([
	        'alert py-2 px-2 border rounded text-center',
	        'border-green-500 bg-green-500/20 text-green-600' => $type == 'success',
	        'border-red-500 bg-red-500/20 text-red-600' => $type == 'error',
	    ])->merge() }}
>{{ $message }}</p>
