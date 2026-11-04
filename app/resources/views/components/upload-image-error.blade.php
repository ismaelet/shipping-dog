<div
    x-data="{ message: null }"
    x-on:upload-image-error.window="message = $event.detail.message"
    x-on:livewire-upload-start.window="message = null"
    x-show="message"
    x-cloak
    {{ $attributes }}
>
    <x-alert><span x-text="message"></span></x-alert>
</div>
