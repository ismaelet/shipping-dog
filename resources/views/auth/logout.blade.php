<x-templates.form :title="$title">
    <x-slot:body>
        <p class="text-center">
            Desideri davvero disconnetterti?
        </p>
    </x-slot:body>

    <x-slot:footer>
        <x-form.button type="submit">Esci</x-form.button>
        <x-form.button href="/profile" variant="secondary">Indietro</x-form.button>
    </x-slot:footer>
</x-templates.form>
