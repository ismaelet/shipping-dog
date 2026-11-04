@pushOnce('styles')
    <style>
        .follow-button {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375em 0.75em;
            background-color: alpha(from currentColor / 5%);
            border: 1px solid currentColor;
            border-radius: var(--radius-md);
            color: var(--color-detail-1-400);
            font-size: var(--text-sm);
            text-box: trim-both cap alphabetic;
            cursor: pointer;
            transition: color 0.2s;

            &:hover {
                color: var(--color-detail-1-300);

                &[data-following='true'] {
                    color: var(--color-danger-400);
                }
            }
        }
    </style>
@endpushOnce

<div @class(['hidden' => ! $isVisible])>
    @if ($isVisible)
        @if ($isAuthenticated)
            <button
                type="button"
                wire:loading.attr="disabled"
                wire:target="follow,unfollow"
                class="follow-button group"
                wire:click="{{ $isFollowing ? 'unfollow' : 'follow' }}"
                data-following="{{ $isFollowing ? 'true' : 'false' }}"
            >
                @if ($isFollowing)
                    <x-icon-remove class="size-3" />
                    <span class=""> non seguire </span>
                @else
                    <x-icon-add class="size-3" />
                    <span>segui</span>
                @endif
            </button>
        @else
            <a href="{{ route('login') }}" class="follow-button">
                <x-icon-add class="size-3" />
                segui
            </a>
        @endif
    @endif
</div>
