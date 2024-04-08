@php
    use App\Models\Ecommerce\Seller;

    $awaitingSellers = Seller::awaitingApproval();
@endphp

<x-templates.dashboard.dashboard-panel title="Negozi da Valutare" subtitle="la lista dei negozi in attesa di valutazione"
    class="col-span-3" variant="big">

    <x-slot name="content">
        <div class="grid gap-6 mt-4">
            @if ($awaitingSellers->count())
                @foreach ($awaitingSellers as $seller)
                    <form class="flex items-center text-sm" method="POST">
                        <span class="w-12 aspect-square">
                            <img src="{{ $seller->icon() }}"
                                class="h-full w-full object-cover rounded-full bg-surface-500 " alt="">
                        </span>

                        <div class="ml-4 grid gap-1">
                            <p class="font-heading text-base font-medium leading-none text-neutral-700">
                                {{ $seller->name }}</p>

                            <div class="flex gap-2 items-center">
                                <button type="submit" class="action-button text-success">accetta</button>
                                <button type="submit" class="action-button text-error">rifiuta</button>
                                <a href="{{ $seller->url() }}" class="action-button text-blue">visualizza</a>
                            </div>
                        </div>

                        <div class="ml-auto text-base">
                            <span class="text-neutral-700 font-medium">{{ $seller->designs->count() }}</span> design
                        </div>
                    </form>
                @endforeach
            @else
                <p class="">Non c'è nessun negozio da valutare</p>
            @endif
        </div>
    </x-slot>

</x-templates.dashboard.dashboard-panel>
