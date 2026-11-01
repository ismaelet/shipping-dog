<section class="address-form card">
    <x-heading variant="article" class="">Indirizzo</x-heading>

    <div class="grid gap-4">
        <x-input
            name="full_name"
            placeholder="Nominativo di spedizione"
            :value="$address->full_name ?? ''"
            :required="true"
            :trim="true"
        />

        <div @class([
            'field grid gap-[inherit] md:grid-cols-2',
            'hidden!' => ! $isShipped,
        ])>
            <x-input
                type="text"
                id="inp_addr"
                class="input delivery-input"
                name="address"
                placeholder="Indirizzo"
                value="{{ $address->address ?? '' }}"
                maxlength="255"
                autocomplete="address-line1"
                :required="$isShipped"
            />

            <x-input
                type="text"
                id="inp_num"
                class="input delivery-input"
                name="number"
                placeholder="Numero civico"
                value="{{ $address->number ?? '' }}"
                maxlength="10"
                :required="$isShipped"
            />
        </div>

        <div @class([
            'hidden!' => ! $isShipped,
        ])>
            <x-input
                type="text"
                class="input delivery-input w-full"
                name="city"
                placeholder="Città"
                value="{{ $address->city ?? '' }}"
                maxlength="255"
                autocomplete="address-level2"
                :required="$isShipped"
            />
        </div>

        <div @class([
            'field grid gap-[inherit] md:grid-cols-2',
            'hidden!' => ! $isShipped,
        ])>
            <x-input
                type="text"
                class="input delivery-input"
                name="zip"
                placeholder="Codice postale"
                value="{{ trim($address->zip ?? '') }}"
                maxlength="5"
                autocomplete="postal-code"
                :required="$isShipped"
            />
            <x-select name="region" class="delivery-input w-full" placeholder="Provincia" :required="$isShipped">
                <option value="" class="opacity-50" disabled @selected(empty($address))>Provincia</option>

                @foreach ($provinces as $province)
                    <option value="{{ $province->tag }}" @selected($address?->region == $province->tag)>
                        {{ $province->name . ' (' . $province->tag . ')' }}
                    </option>
                @endforeach
            </x-select>
        </div>

        <x-input
            type="email"
            class="input"
            name="email"
            placeholder="Indirizzo e-mail"
            value="{{ $address->email ?? '' }}"
            maxlength="50"
            required
            autocomplete="email"
        />
        <x-input
            type="text"
            class="input js-input-no-space"
            name="phone"
            placeholder="Numero di telefono"
            value="{{ $address->phone ?? '' }}"
            maxlength="30"
            autocomplete="tel"
            required
        />

        <div @class([
            'hidden!' => ! $isShipped,
        ])>
            <x-textarea
                class="input w-full"
                name="courier_notes"
                placeholder="Note per il corriere (opzionale)"
                maxlength="250"
            >{{ $cart->courier_notes ?? '' }}</x-textarea>
        </div>
    </div>
</section>
