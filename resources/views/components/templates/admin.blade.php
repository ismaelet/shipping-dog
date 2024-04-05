@pushOnce('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&display=swap');

        :root {
            --color-primary-400-hsl: 215deg 70% 60%;
            --clr-primary-hsl-500: 229, 70%, 55%;

            --clr-neutral-hsl-100: 0, 0%, 0%;
            --clr-neutral-hsl-150: 0, 0%, 6%;
            --clr-neutral-hsl-200: 0, 0%, 12%;
            --clr-neutral-hsl-300: 0, 0%, 87%;
            --clr-neutral-hsl-400: 0, 0%, 67%;
            --clr-neutral-hsl-500: 0, 0%, 47%;
            --clr-neutral-hsl-600: 0, 0%, 31%;
            --clr-neutral-hsl-700: 0, 0%, 13%;
            --clr-neutral-hsl-900: 0, 0%, 100%;

            --clr-good-400: green;
            --clr-good-500: darkgreen;
            --clr-bad-400: red;
            --clr-bad-500: darkred;

            --clr-surface-100: var(--clr-neutral-100);

            --clr-background-hsl: var(--clr-neutral-hsl-100);

            --ff-default: 'Open Sans', sans-serif;
            --ff-titles: 'Poppins', sans-serif;

            --fc-400: var(--clr-neutral-500);
            --fc-700: hsl(--clr-neutral-700);

            --fw-light: 300;
            --fw-regular: 400;
            --fw-medium: 500;
            --fw-semibold: 600;
            --fw-bold: 700;
            --fw-black: 900;

            --fs-100: 0.5rem;
            --fs-200: 0.625rem;
            --font-size-300: 0.875rem;
            --fs-400: 1rem;
            --fs-450: 1.1rem;
            --fs-500: 1.25rem;
            --fs-600: 1.5rem;
            --fs-650: 1.75rem;
            --fs-700: 2rem;
            --fs-750: 2.5rem;
            --fs-800: 3rem;
            --fs-850: 3.5rem;
            --fs-900: 4rem;
            --fs-950: 5rem;

            --border-clr-400: var(--clr-neutral-300);
            --border-clr-700: var(--clr-neutral-700);

            --border-width: 1px;

            --border-radius-400: 0.5rem;
            --border-radius-700: 1rem;
            --border-radius-800: 2rem;
            --border-radius-1000: 100vw;

            --border: var(--border-width) solid var(--border-clr-400);

            --shadow-clr: var(--clr-neutral-hsl-900);
            --shadow-intensity: 0.1;
            --shadow-400: 0 1rem 2rem hsla(var(--shadow-clr), var(--shadow-intensity));
            --shadow-big-400: 0 2rem 4rem hsla(var(--shadow-clr), var(--shadow-intensity));

            --button-fw: var(--fw-medium);
            --button-border-radius: var(--border-radius-400);
            --button--primary-fc: var(--clr-neutral-900);
            --button--secondary-border-width: var(--border-width);

            --inp-height: 3rem;
            --inp-border-width: var(--border-width);
            --inp-border-radius: var(--border-radius-400);
            --inp-shadow: 0 0 5px rgba(16, 114, 191, 0.1);
            --inp-clr-bgr: var(--clr-surface-600);
            --inp-clr-accent: var(--color-primary-400);
            --inp-color: var(--fc-700);
            --inp-fs: var(--fs-400);
        }

        html[data-color-scheme='dark'] {
            --fc-400: hsl(var(--theme-h), 19%, 72%);
            --fc-700: hsl(212, 29%, 92%);

            --fw-semibold: 500;
            --fw-bold: 500;

            --theme-h: 216;

            --clr-neutral-hsl-100: 0, 0%, 0%;
            --clr-neutral-hsl-150: 0, 0%, 6%;
            --clr-neutral-hsl-200: 0, 0%, 12%;
            --clr-neutral-hsl-300: 0, 0%, 87%;
            --clr-neutral-hsl-400: 0, 0%, 67%;
            --clr-neutral-hsl-500: 0, 0%, 47%;
            --clr-neutral-hsl-600: 0, 0%, 31%;
            --clr-neutral-hsl-700: 0, 0%, 13%;
            --clr-neutral-hsl-900: 0, 0%, 0%;

            --clr-background-hsl: var(--theme-h), 40%, 9%;

            --clr-surface-300: hsl(var(--theme-h), 36%, 11%);
            --clr-surface-400: hsl(var(--theme-h), 32%, 13%);
            --clr-surface-500: hsl(var(--theme-h), 28%, 16%);
            --clr-surface-600: hsl(var(--theme-h), 28%, 20%);
            --clr-surface-700: hsl(var(--theme-h), 20%, 25%);

            --border-clr-400: hsl(var(--theme-h), 30%, 20%);
            --border-clr-500: hsl(var(--theme-h), 30%, 50%);
            --border-clr-700: var(--border-clr-500);

            --shadow-intensity: 0.2;

            --button--primary-fc: #fff;
        }


        h1,
        h2,
        h3 {
            letter-spacing: 0.03em;
            line-height: 1;
        }

        p strong,
        p .fw-bold,
        p .fw-black {
            color: var(--fc-700);
        }

        .title {
            font-size: var(--fs-900);
            font-weight: var(--fw-bold);
        }

        .subtitle {
            font-size: var(--fs-700);
            font-weight: var(--fw-medium);
        }

        .section-heading {
            font-size: min(2.5rem, 10vw);
            font-weight: var(--fw-semibold);
        }

        .section-subtitle {
            font-size: var(--fs-500);
        }

        .subsection-heading {
            font-size: var(--fs-500);
        }

        .text {
            font-size: var(--font-size-300);
        }

        .contrasting-background {
            --fc-400: var(--clr-neutral-100);
            --fc-700: var(--clr-neutral-100);
            color: var(--fc-400);
        }

        html[data-color-scheme='light'] .dark-background {
            --fc-400: var(--clr-neutral-100);
            --fc-700: var(--clr-neutral-100);
            color: var(--fc-400);
        }

        html[data-color-scheme='dark'] .light-background {
            --fc-400: var(--clr-neutral-100);
            --fc-700: var(--clr-neutral-100);
            color: var(--fc-400);
        }

        main {
            display: grid;
            grid-template-columns: max-content minmax(0, 1fr);
            background-color: var(--clr-background);
        }

        .menu {
            --_padding: 2.5rem;
            --_icon-size: 1.1rem;
            position: relative;
            width: 18rem;
            transition: width 0.4s;
        }

        [data-color-scheme="dark"] .menu {
            background-color: transparent;
        }

        .menu__content {
            height: 100svh;
            position: sticky;
            top: 0;
            overflow: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--clr-surface-700) var(--clr-surface-400);
        }

        .menu__group {
            display: grid;
            gap: 0;
        }

        .menu__header {
            display: grid;
            gap: 0.5rem;
            margin-bottom: 5rem;
            justify-items: center;
            padding-top: var(--_padding);
            padding-inline: var(--_padding);
            color: var(--fc-700);
            text-align: center;
        }

        .menu__logo {
            width: 100%;
            max-height: 3rem;
        }

        .menu__title {
            font-size: var(--fs-600);
            font-weight: var(--fw-semibold);
            letter-spacing: 0.05em;
        }

        [data-color-scheme="dark"] .menu__title {
            font-weight: var(--fw-medium);
        }

        .menu__divider {
            display: block;
            margin-inline: var(--_padding);
            border-top: var(--border);
        }

        .menu-item {
            display: grid;
            grid-template-rows: max-content 0fr;
            padding-right: 1rem;
            font-size: var(--fs-300);
            transition: grid-template-rows 0.3s;
        }

        .menu-item__header {
            display: grid;
            grid-template-columns: var(--_padding) var(--_icon-size) 1fr;
            align-items: center;
            position: relative;
            cursor: pointer;
            transition: grid-template-columns 0.4s;
        }

        .menu-item.active .menu-item__header {
            color: var(--fc-700);
        }

        .menu-item__header::before {
            content: "";
            width: 3px;
            height: 50%;
            opacity: 0;
            background-color: var(--clr-primary-400);
            box-shadow: 3px 0 1rem 0 var(--clr-primary-400),
                3px 0 2rem 0 var(--clr-primary-400);
            transition: opacity 0.3s;
        }

        .menu-item.active .menu-item__header::before {
            opacity: 1;
        }

        .menu-item__title {
            white-space: nowrap;
            overflow: hidden;
            margin-left: 1rem;
            font-family: var(--ff-titles);
            line-height: 3.5rem;
            transition: color 0.3s;
        }

        [data-color-scheme="light"] {
            font-weight: var(--fw-medium);
        }

        .menu__dropdown {
            overflow: hidden;
        }

        .menu-subitem {
            display: grid;
            grid-template-columns: 0.5rem minmax(0, 1fr);
            align-items: center;
            gap: 0.5rem;
            padding-left: calc(var(--_padding) + 2rem);
            opacity: 0;
            font-family: var(--ff-titles);
            overflow: hidden;
            font-size: var(--font-size-300);
            white-space: nowrap;
            text-overflow: ellipsis;
            transition: opacity 0.3s;
        }

        .menu-item.active .menu-subitem {
            opacity: 1;
        }

        .menu-subitem::before {
            content: "";
            aspect-ratio: 1;
            border-radius: 2px;
        }

        .menu-subitem:nth-child(5n + 1):before {
            background-color: #ffc861;
        }

        .menu-subitem:nth-child(5n + 2):before {
            background-color: #a1d0eb;
        }

        .menu-subitem:nth-child(5n + 3):before {
            background-color: #e08a8a;
        }

        .menu-subitem:nth-child(5n + 4):before {
            background-color: #c99bd1;
        }

        .menu-subitem:nth-child(5n + 5):before {
            background-color: #d1a39b;
        }

        .menu-subitem__title {
            line-height: 2.5rem;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .menu-subitem__title a {
            transition: color 0.2s;
        }

        .menu-subitem__title.active a,
        .menu-subitem__title a:hover {
            color: #fff;
        }

        .menu__open {
            --icon-size: 50%;
            position: absolute;
            inset: 0 0 0 100%;
            translate: -50% 0;
            z-index: 1;
            margin-block: auto;
            width: 2.8rem;
            color: var(--fc-700);
            aspect-ratio: 1;
            background-color: var(--clr-neutral-150);
            border-radius: 50%;
            cursor: pointer;
        }

        [data-color-scheme="dark"] .menu__open {
            background-color: var(--clr-surface-500);
        }

        .menu__open .icon-embed {
            transition: transform 0.1s;
        }

        .menu.open .menu__open .icon-embed {
            transform: rotate(-180deg);
        }

        @media (min-width: 1281px) {
            .menu__open {
                display: none;
            }
        }

        .page-content {
            min-height: 100svh;
            padding: 2rem;
            overflow: hidden;
        }

        .page-wrapper {
            min-width: 250px;
        }

        .panel {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            grid-template-rows: max-content 1fr;
            gap: 1rem;
            padding: 2rem;
            background-color: var(--clr-neutral-100);
            border-radius: var(--border-radius-700);
            box-shadow: var(--shadow-big-400);
        }

        [data-color-scheme="dark"] .panel {
            background-color: var(--clr-surface-400);
        }

        .panel:has(.panel__footer) {
            grid-template-rows: max-content 1fr max-content;
        }

        .panel__header:has(.panel__icon) {
            display: grid;
            grid-template-columns: max-content minmax(0, 1fr);
            align-items: center;
            gap: 1rem;
        }

        .panel__icon {
            --icon-size: 2.5rem;
            color: var(--fc-700);
            justify-self: left;
        }

        .panel__title {
            font-weight: var(--fw-medium);
            font-size: var(--fs-600);
        }

        .panel__title--outside {
            margin-left: 2rem;
            margin-bottom: 0.5rem;
        }

        .panel__footer {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            align-content: flex-end;
            gap: 0.5rem;
        }

        .panel .button {
            width: 100%;
            border-radius: var(--border-radius-400);
        }

        @media (min-width: 1281px) {
            .menu-item.active {
                grid-template-rows: max-content 1fr;
            }
        }

        @media (max-width: 1280px) {
            .menu {
                --_padding: 2rem;
                width: 15rem;
            }

            [data-color-scheme="dark"] .menu {
                background-color: var(--clr-surface-300);
            }

            .menu:not(.open) {
                width: calc(var(--_padding) * 2 + var(--_icon-size));
            }

            .menu__header {
                padding-inline: unset;
            }

            .menu__logo {
                width: 2rem;
            }

            .menu__title {
                display: none;
            }

            .menu:not(.open) .menu-item__header {
                grid-template-columns: var(--_padding) var(--_icon-size) 0fr;
            }

            .menu.open .menu-item.active {
                grid-template-rows: max-content 1fr;
            }
        }

        @media (max-width: 480px) {
            .menu {
                --_padding: 1.5rem;
                width: 13rem;
            }
        }

        .button-shutdown {
            width: 100%;
            padding-inline: unset;
            box-shadow: var(--shadow);

            @media (max-width: 1280px) {
                & span {
                    display: none;
                }
            }
        }

        .menu__group:has(> .button-shutdown) {
            padding-inline: var(--_padding);
            margin-top: 1rem;

            @media (max-width: 1280px) {
                padding-inline: 1rem;
            }
        }
    </style>
@endpushOnce

<x-templates.empty>
    <!-- Navigation bar -->
    <x-admin.navigation />

    <!-- Body -->
    <section class="page-content">
        <div class="page-wrapper">

            {{ $slot }}

        </div>
    </section>
</x-templates.empty>
