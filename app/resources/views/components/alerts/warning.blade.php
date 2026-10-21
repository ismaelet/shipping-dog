@props(['message' => null])

<p class="border-danger-400 text-danger-400 rounded-sm border p-4 text-center">{{ $message ?? $slot }}</p>
