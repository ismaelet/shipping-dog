@php
    use App\Models\User;

    $newUsers = User::orderBy('created_at', 'desc')->take(6)->get();

@endphp

<x-dashboard.widget title="Nuovi iscritti" subtitle="la lista degli ultimi utenti registrati" class="col-span-3"
    variant="big">

    <x-slot name="content">
        <div class="grid gap-6 mt-4">

            @if ($newUsers->count())
                @foreach ($newUsers as $user)
                    <form class="flex items-center text-sm" method="POST">
                        <span class="w-12 aspect-square">
                            <img src="{{ $user->icon() }}"
                                class="h-full w-full object-cover rounded-full bg-surface-500 " alt="">
                        </span>

                        <div class="ml-4 grid gap-1">
                            <p class="font-heading text-sm font-medium leading-none text-neutral-700">
                                {{ $user->fullName() ?: $user->email }}
                            </p>

                            <div class="flex gap-2 items-center">
                                <p class="text-sm">{{ $user->fullName() ? $user->email : '' }}</p>
                            </div>
                        </div>

                        <div class="ml-auto text-base">
                            <span class="text-neutral-700 text-sm">{!! $user->registrationDate() !!}</span>
                        </div>
                    </form>
                @endforeach
            @else
                <p class="">Non si è registrato ancora nessun utente</p>

            @endif

        </div>
    </x-slot>

</x-dashboard.widget>
