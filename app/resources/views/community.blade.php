@php
    use App\Models\Ecommerce\Design\CustomerPhoto;

    $customerPhotos = CustomerPhoto::with('design', 'design.main_product')->orderByDesc('id')->get();
@endphp

<x-layouts.main>
    <section class="py-20">
        <div class="text-center">
            <div class="">
                <x-heading variant="main">La community</x-heading>
            </div>

            <p class="mx-auto mt-5 max-w-120 leading-7">
                Tagga
                <a
                    href="{{ config('custom.contacts.instagram.url') }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="text-primary-500 dark:text-heading-400 font-semibold transition-colors hover:text-orange-500"
                >
                    @shippingdog
                </a>
                o inviaci una foto del tuo look via
                <a
                    href="{{ config('custom.contacts.email.url') }}"
                    class="text-primary-500 dark:text-heading-400 font-semibold transition-colors hover:text-orange-500"
                >
                    e-mail
                </a>
                e ricevi un codice <span class="text-primary-400 font-semibold">sconto del 15%</span>.
            </p>
        </div>

        <div class="mt-12">
            <div class="grid grid-cols-2 gap-8 sm:columns-[15rem]">
                @foreach ($customerPhotos as $customerPhoto)
                    <x-community.photo-card :customer-photo="$customerPhoto" />
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.main>
