@props([
    'search',
    'storedName',
    'storedValue',
    'shownValue',
    'where',
    'exclude',
    'placeholder' => 'Cerca...',
    'required' => false,
])

@pushOnce('styles')
    <style>
        .search-input {
            position: relative;
        }

        .search-input__dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 1rem);
            width: 100%;
            z-index: 10;
            overflow: hidden;
            background-color: var(--color-surface-400);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius-400);
            box-shadow: var(--shadow-400);
            text-align: left;

            &.active {
                display: block;
            }
        }

        .search-input__item {
            padding: 0.6rem 0.6rem;
            cursor: pointer;
            transition: background-color 0.1s;
        }

        .search-input__item.focused,
        .search-input__item:hover {
            background-color: var(--color-surface-500);
        }
    </style>
@endpushOnce

@pushOnce('scripts')
    <script defer>
        function highlight_search_result(text, search) {
            let pattern = new RegExp('(' + search + ')', 'ig')

            return text.replace(pattern, '<span class="font-bold text-neutral-700">$1</span>')
        }

        document.addEventListener('DOMContentLoaded', function() {
            /**
             * Fetch the search results
             */
            document.querySelectorAll('.js-search-show').forEach((element) => {
                element.addEventListener('keyup', async (event) => {
                    // Prevent arrow keys from triggering the search
                    if (['ArrowUp', 'ArrowDown'].includes(event.key)) {
                        return false;
                    }

                    // Set the search parameters
                    const searchBlock = element.closest('.js-search-input')
                    const storedInput = searchBlock.querySelector('.js-search-store')
                    const shownInput = element
                    const dropDown = searchBlock.querySelector('.js-search-dropdown')
                    const endpoint = '/api/' + searchBlock.dataset.endpoint
                    const filter = shownInput.value
                    const exclude = searchBlock.dataset.exclude
                    const where = searchBlock.dataset.where ? JSON.parse(searchBlock.dataset
                        .where) : {}

                    // Empty the previous results
                    dropDown.innerHTML = ''
                    storedInput.value = ''

                    // Fetch the results
                    axios.get(endpoint, {
                            params: {
                                filter: filter,
                                exclude: exclude,
                                where: where
                            }
                        })
                        .then(response => {
                            // Get the results
                            const results = response.data.data
                            const count = results.length

                            if (count) {
                                dropDown.innerHTML = ''

                                // Populate the dropdown
                                for (const result of results) {
                                    const itemText = highlight_search_result(result.name,
                                        filter)

                                    dropDown.insertAdjacentHTML('beforeend',
                                        `<p class="search-input__item js-search-item" data-id="${result.id}" data-text="${result.name}" tabindex="-1">${itemText}</p>`
                                    )

                                    dropDown.classList.add('active')
                                }
                            } else {
                                // Hide the dropdown
                                dropDown.classList.remove('active')
                            }
                        })
                        .catch(error => {
                            // Log the error
                            console.error(error.response.data.message);
                        });
                })
            })

            /**
             * Select the search result
             */
            document.querySelectorAll('.js-search-input').forEach((element) => {
                element.addEventListener('click', (event) => {
                    if (event.target.classList.contains('js-search-item')) {
                        const searchBlock = event.target.closest('.js-search-input')
                        const storedInput = searchBlock.querySelector('.js-search-store')
                        const shownInput = searchBlock.querySelector('.js-search-show')
                        const dropDown = searchBlock.querySelector('.js-search-dropdown')

                        storedInput.value = event.target.dataset.id
                        shownInput.value = event.target.dataset.text

                        storedInput.dispatchEvent(new Event('input'))
                        shownInput.focus()

                        dropDown.classList.remove('active')
                    }
                })
            })

            /**
             * Keyboard navigation
             */
            document.querySelectorAll('.js-search-input').forEach((element) => {
                element.addEventListener('keydown', (event) => {
                    const searchBlock = event.target.closest('.js-search-input')
                    const dropDown = searchBlock.querySelector('.js-search-dropdown')
                    const items = dropDown.querySelectorAll('.js-search-item')
                    const focused = dropDown.querySelector('.focused')

                    if (event.key == 'ArrowDown') {
                        if (focused) {
                            focused.classList.remove('focused')

                            if (focused.nextElementSibling) {
                                focused.nextElementSibling.classList.add('focused')
                            } else {
                                items[0].classList.add('focused')
                            }
                        } else {
                            items[0].classList.add('focused')
                        }
                    } else if (event.key == 'ArrowUp') {
                        if (focused) {
                            focused.classList.remove('focused')

                            if (focused.previousElementSibling) {
                                focused.previousElementSibling.classList.add('focused')
                            } else {
                                items[items.length - 1].classList.add('focused')
                            }
                        } else {
                            items[items.length - 1].classList.add('focused')
                        }
                    } else if (event.key == 'Enter') {
                        if (focused) {
                            event.preventDefault()
                        }

                        if (focused) {
                            focused.click()
                        }
                    }
                })
            })
        })
    </script>
@endpushOnce

<div {{ $attributes->class(['search-input', 'grid', 'js-search-input']) }} data-endpoint="{{ $search }}"
    {!! isset($where) ? 'data-where="' . htmlspecialchars(json_encode($where), ENT_QUOTES, 'UTF-8') . '"' : '' !!} {!! isset($exclude) ? 'data-exclude="' . implode(',', $exclude) . '"' : '' !!}>

    <input type="hidden" class="js-search-store" name="{{ $storedName }}" value="{{ $storedValue ?? '' }}"
        @required($required)>

    <x-inputs.input class="js-search-show" :name="$shownName ?? null" :value="$shownValue ?? ''" :placeholder="$placeholder" :required="$required ?? null"
        autocomplete="off" />

    <div class="search-input__dropdown js-search-dropdown">
        <!-- Populated after fetch -->
    </div>
</div>
