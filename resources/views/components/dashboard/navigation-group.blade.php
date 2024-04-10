{{-- 
-- navigationGroup: [
	string 'name',
	string 'icon',
	?string 'url' => null,
	bool 'checked' => false,
	array 'sections' => [
			string 'name',
			string 'url',
			bool 'checked' => false,
		]
	]
--}}
@props(['navigationGroup'])


@pushOnce('styles')
    <style>
        @scope (.navigation-group)

            {

            .item {
                grid-template-rows: max-content 0fr;
                overflow: hidden;
                transition: grid-template-rows 0.4s;

                &:has(:checked) {
                    grid-template-rows: max-content 1fr;

                    .subitem {
                        opacity: 1;
                    }
                }
            }

            .item-header {
                display: grid;
                grid-template-columns: 1.1rem 1fr;
                transition: grid-template-columns 0.4s, color .3s;

                .item:has(:checked) & {
                    color: var(--color-neutral-700);
                }
            }

            .item-title {
                white-space: nowrap;
                overflow: hidden;
                line-height: 3.5rem;
            }

            .dropdown {
                overflow: hidden;
            }

            .subitem {
                display: grid;
                grid-template-columns: 0.5rem minmax(0, 1fr);
                align-items: center;
                gap: 0.5rem;
                padding-left: 2rem;
                opacity: 0;
                overflow: hidden;
                font-size: var(--font-size-300);
                white-space: nowrap;
                text-overflow: ellipsis;
                transition: opacity 0.3s;
            }

            .subitem::before {
                content: "";
                aspect-ratio: 1;
                border-radius: 2px;
            }

            .subitem:nth-child(5n + 1):before {
                background-color: #ffc861;
            }

            .subitem:nth-child(5n + 2):before {
                background-color: #a1d0eb;
            }

            .subitem:nth-child(5n + 3):before {
                background-color: #e08a8a;
            }

            .subitem:nth-child(5n + 4):before {
                background-color: #c99bd1;
            }

            .subitem:nth-child(5n + 5):before {
                background-color: #d1a39b;
            }

            .subitem-title {
                line-height: 2.5rem;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;
            }

            .subitem-title a {
                transition: color 0.2s;
            }

            .subitem-title.active a,
            .subitem-title a:hover {
                color: var(--color-primary-400);

                [data-color-scheme="dark"] & {
                    color: #fff;
                }
            }
        }
    </style>
@endpushOnce

<ul class="navigation-group contents">

    <div class="">
        @foreach ($navigationGroup as $item)
            <label>
                <li class="item grid">
                    <input type="checkbox" name="admin_category" class="hidden js-radio-checkbox">

                    @if (isset($item['url']))
                        <a class="item-header items-center cursor-pointer" href="{{ $item['url'] }}">
                            {!! embed_icon($item['icon']) !!}
                            <p class="item-title ml-4 font-heading">{{ $item['name'] }}</p>
                        </a>
                    @else
                        <div class="item-header items-center cursor-pointer">
                            {!! embed_icon($item['icon']) !!}
                            <p class="item-title ml-4 font-heading">{{ $item['name'] }}</p>
                        </div>

                        <!-- Dropdown -->
                        <ul class="dropdown">

                            @foreach ($item['sections'] as $subitem)
                                <li class="subitem">
                                    <p class="subitem-title {{ $subitem['checked'] ? 'active' : '' }}">
                                        <a href="{{ $subitem['url'] }}">{{ $subitem['name'] }}</a>
                                    </p>
                                </li>
                            @endforeach

                        </ul>
                    @endif
                </li>
            </label>
        @endforeach
    </div>

    <span class="border-b"></span>
</ul>
