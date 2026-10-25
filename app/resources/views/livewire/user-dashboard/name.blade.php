@php
    $user = auth()->user();
    $email = $user->email;
    $username = $user->name;
    $fullName = $user->fullName();
    $name = $username ?: $fullName ?: null;
@endphp

<div class="text-center">
    @if ($name)
        <x-heading.component class="text-center text-lg break-all">Ciao, {{ $name }}</x-heading.component>
    @endif

    @if ($username || $fullName)
        <p class="text-text-400 text-sm">{{ $email }}</p>
    @endif
</div>
