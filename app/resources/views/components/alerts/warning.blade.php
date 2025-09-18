@props(['message' => null])

<p class="border-error-400 text-error-400 rounded-sm border p-4 text-center">{{ $message ?? $slot }}</p>
