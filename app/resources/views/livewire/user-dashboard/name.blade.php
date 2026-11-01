@php
    $user = auth()->user();
    $email = $user->email;
    $username = $user->name;
    $fullName = $user->fullName();
    $name = $username ?: $fullName ?: null;
@endphp

<div class="text-center">
    @if ($name)
        <x-heading variant="article" class="text-center break-all">Ciao, {{ $name }}</x-heading>
    @endif

    @if ($username || $fullName)
        <p class="text-text-400 text-sm">{{ $email }}</p>
    @endif
</div>
