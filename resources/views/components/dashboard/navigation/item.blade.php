@props(['item'])

@php
    $checked = $item['route_name'] == Route::currentRouteName();
@endphp

@pushOnce('styles')
    <style>
        .navigation-item {
            display: grid;
            grid-template-columns: 0.5rem minmax(0, 1fr);
            align-categorys: center;
            gap: 0.5rem;
            padding-left: 2rem;
            opacity: 0;
            overflow: hidden;
            font-size: var(--font-size-300);
            white-space: nowrap;
            text-overflow: ellipsis;
            transition: opacity 0.3s;

            ul:has(:checked) & {
                opacity: 1;
            }
        }

        .navigation-item::before {
            content: "";
            aspect-ratio: 1;
            border-radius: 2px;
        }

        .navigation-item:nth-child(5n + 1):before {
            background-color: #ffc861;
        }

        .navigation-item:nth-child(5n + 2):before {
            background-color: #a1d0eb;
        }

        .navigation-item:nth-child(5n + 3):before {
            background-color: #e08a8a;
        }

        .navigation-item:nth-child(5n + 4):before {
            background-color: #c99bd1;
        }

        .navigation-item:nth-child(5n + 5):before {
            background-color: #d1a39b;
        }

        .navigation-item-title {
            line-height: 2.5rem;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .navigation-item-title a {
            transition: color 0.2s;
        }

        .navigation-item-title.active a,
        .navigation-item-title a:hover {
            color: var(--color-primary-400);

            [data-color-scheme="dark"] & {
                color: #fff;
            }
        }
    </style>
@endpushOnce

<li class="navigation-item flex items-center">
    <input type="checkbox" name="navigation_item" class="hidden js-radio-checkbox" @checked($checked)>

    <p class="navigation-item-title {{ $checked ? 'active' : '' }}">
        <a href="{{ $item['url'] }}">{{ $item['name'] }}</a>
    </p>
</li>
