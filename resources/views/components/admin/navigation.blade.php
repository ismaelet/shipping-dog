@php

    use App\Models\Admin\AdminCategory;
    use App\Models\Admin\AdminSection;

    // fetch current section
    $currentSection = false;
    $currentPathFull = explode('admin', Request::path());
    $currentPathFull = $currentPathFull[1] ?? null;

    if ($currentPathFull) {
        $currentPath = explode('/', $currentPathFull, 2);
        $currentSection = DB::row('* FROM admin_sections WHERE category=? AND slug=?', [
            $currentPath[0],
            $currentPath[1],
        ]);
    }

    // fetch categories
    $menu['categories'] = [];

    $categories = is_supervisor()
        ? AdminCategory::orderBy('manager')->orderBy('sort')->orderBy('name')->get()
        : AdminCategory::where('manager', 0)->orderBy('manager')->orderBy('sort')->orderBy('name')->get();

    foreach ($categories as $categoryRecord) {
        $category = [];
        $category['name'] = $categoryRecord->name;
        $category['slug'] = $categoryRecord->slug;
        $category['checked'] = $currentSection && $category['slug'] == $currentSection['category'];

        $sections = is_supervisor()
            ? AdminSection::where('category', $category['slug'])
                ->where('hidden', 0)
                ->orderBy('sort')
                ->orderBy('name')
                ->get()
            : AdminSection::where('category', $category['slug'])
                ->where('manager', 0)
                ->where('hidden', 0)
                ->orderBy('sort')
                ->orderBy('name')
                ->get();

        foreach ($sections as $sectionRecord) {
            $section = [];
            $section['name'] = $sectionRecord['name'];
            $section['checked'] =
                $currentSection &&
                $category['checked'] &&
                ($sectionRecord['slug'] == $currentSection['slug'] ||
                    ($sectionRecord['hidden'] && str_replace('add', 'list', $currentSection['slug'])));
            $section['url'] = empty($sectionRecord['url'])
                ? '/admin/' . $sectionRecord['category'] . '/' . $sectionRecord['slug']
                : $sectionRecord['url'];

            $category['sections'][] = $section;
        }

        $menu['categories'][] = $category;
    }

    $navigationLogo = embed_svg('logo/logo', ['menu__logo']);
    $navigationLogo =
        $navigationLogo ?: '<img class="menu__logo image-fit" src="' . (APP_LOGO_DIR . 'logo.png') . '"></img>';
@endphp

@pushOnce('styles')
    <style>
        .menu__group:has(> .button-shutdown) {
            padding-inline: var(--_padding);
            margin-top: 1rem;

            @media (max-width: 1280px) {
                padding-inline: 1rem;
            }
        }

        .button-shutdown {
            width: 100%;
            padding-inline: unset;
            border-radius: var(--border-radius-400);

            @media (max-width: 1280px) {
                & span {
                    display: none;
                }
            }
        }
    </style>
@endpushOnce

@pushOnce('scripts')
    <script defer>
        document.addEventListener("DOMContentLoaded", function() {
            const menuSelector = ".js-menu";
            const menuCategorySelector = ".js-menu-category";
            const toggleMenuSelector = ".js-toggle-menu";
            const openMenuSelector = ".js-open-menu";
            const openMenuCategorySelector = ".js-open-menu-category";

            document.querySelectorAll(openMenuCategorySelector).forEach((element) => {
                element.addEventListener("click", () => {
                    const category = element.closest(menuCategorySelector);

                    toggle_category(category);
                });
            });

            document.querySelector(openMenuSelector).addEventListener("click", () => {
                open_menu();
            });

            document
                .querySelector(openMenuSelector)
                .addEventListener("mouseenter", () => {
                    open_menu();
                });

            document
                .querySelector(openMenuSelector)
                .addEventListener("mouseleave", () => {
                    close_menu();
                });

            document.querySelector(toggleMenuSelector).addEventListener("click", () => {
                toggle_menu();
            });

            function toggle_category(menuCategory) {
                if (!menuCategory.classList.contains("active")) {
                    open_category(menuCategory);
                } else {
                    close_category(menuCategory);
                }
            }

            function open_category(thisMenuCategory) {
                document
                    .querySelectorAll(menuCategorySelector)
                    .forEach((menuCategory) => {
                        close_category(menuCategory);
                    });

                thisMenuCategory.classList.add("active");
            }

            function close_category(menuCategory) {
                menuCategory.classList.remove("active");
            }

            function toggle_menu() {
                if (!document.querySelector(menuSelector).classList.contains("open")) {
                    open_menu();
                } else {
                    close_menu();
                }
            }

            function open_menu() {
                document.querySelector(menuSelector).classList.add("open");
            }

            function close_menu() {
                document.querySelector(menuSelector).classList.remove("open");
            }
        });
    </script>
@endpushOnce

<section class="menu js-menu">
    <div class="menu__content">

        <div class="">
            <!-- Title -->
            <div class="menu__header">
                {!! $navigationLogo !!}
                <h1 class="menu__title">{{ config('app.name') }}</h1>
            </div>

            <!-- Admin sections -->
            <div class="menu__groups grid-flow js-open-menu" data-spacing="400">
                <div class="menu__group">
                    <!-- Home link -->
                    <div class="menu-item">
                        <a class="menu-item__header" href="{{ '/' }}" target="_blank">
                            {!! embed_icon('link') !!}
                            <p class="menu-item__title">Sito pubblico</p>
                        </a>
                    </div>
                </div>

                <span class="menu__divider"></span>

                <div class="menu__group">

                    <!-- Home link -->
                    <div class="menu-item">
                        <a class="menu-item__header" href="<?= route('admin') ?>">
                            {!! embed_icon('dashboard') !!}
                            <p class="menu-item__title">Dashboard</p>
                        </a>
                    </div>

                    <!-- Navigation -->
                    <?php foreach ($menu['categories'] as $category) { ?>

                    <!-- Menu category -->
                    <div class="menu-item <?= $category['checked'] ? 'active' : '' ?> js-menu-category">

                        <!-- Item header -->
                        <div class="menu-item__header js-open-menu-category">
                            <?= embed_icon($category['slug']) ?>
                            <p class="menu-item__title"><?= $category['name'] ?></p>
                        </div>

                        <!-- Dropdown -->
                        <div class="menu__dropdown">

                            <?php foreach ($category['sections'] as $section) { ?>

                            <div class="menu-subitem">
                                <p class="menu-subitem__title <?= $section['checked'] ? 'active' : '' ?>">
                                    <a href="<?= $section['url'] ?>"><?= $section['name'] ?></a>
                                </p>
                            </div>

                            <?php } ?>

                        </div>
                    </div>

                    <?php } ?>

                    </span>
                </div>

                <span class="menu__divider"></span>

                <form class="menu__group" method="POST">
                    <x-form.button variant="secondary" size="small" class="button-shutdown" name="logout">
                        <?= embed_icon('exit') ?>
                        <span>Esci</span>
                    </x-form.button>
                </form>
            </div>

        </div>
    </div>
    <!-- Mobile open menu icon -->
    <div class="menu__open js-toggle-menu">
        <?= embed_icon('navigation/arrow-simple-right') ?>
    </div>
</section>
