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

    $categories = supervisor()
        ? AdminCategory::orderBy('manager')->orderBy('sort')->orderBy('name')->get()
        : AdminCategory::where('manager', 0)->orderBy('manager')->orderBy('sort')->orderBy('name')->get();

    foreach ($categories as $categoryRecord) {
        $category = [];
        $category['name'] = $categoryRecord->name;
        $category['slug'] = $categoryRecord->slug;
        $category['checked'] = $currentSection && $category['slug'] == $currentSection['category'];

        $sections = supervisor()
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
                ? old_url('/' . $sectionRecord['category'] . '/' . $sectionRecord['slug'])
                : $sectionRecord['url'];

            $category['sections'][] = $section;
        }

        $menu['categories'][] = $category;
    }

    $navigationLogo = file_exists(base_path() . ($file = APP_LOGO_DIR . 'logo.svg'))
        ? icon_embed(APP_LOGO_DIR . 'logo', ['menu__logo'])
        : '<img class="menu__logo image-fit" src="' . (APP_LOGO_DIR . 'logo.png') . '"></img>';
@endphp

<section class="menu js-menu">
    <div class="menu__content">

        <div class="">
            <!-- Title -->
            <div class="menu__header">
                <?= $navigationLogo ?>
                <h1 class="menu__title"><?= config('app.name') ?></h1>
            </div>

            <!-- Admin sections -->
            <div class="menu__groups grid-flow js-open-menu" data-spacing="400">
                <div class="menu__group">
                    <!-- Home link -->
                    <div class="menu-item">
                        <a class="menu-item__header" href="<?= route('home') ?>" target="_blank">
                            <?= icon_embed('link') ?>
                            <p class="menu-item__title">Sito pubblico</p>
                        </a>
                    </div>
                </div>

                <span class="menu__divider"></span>

                <div class="menu__group">

                    <!-- Home link -->
                    <div class="menu-item">
                        <a class="menu-item__header" href="<?= route('admin') ?>">
                            <?= icon_embed('home') ?>
                            <p class="menu-item__title">Home</p>
                        </a>
                    </div>

                    <!-- Navigation -->
                    <?php foreach ($menu['categories'] as $category) { ?>

                    <!-- Menu category -->
                    <div class="menu-item <?= $category['checked'] ? 'active' : '' ?> js-menu-category">

                        <!-- Item header -->
                        <div class="menu-item__header js-open-menu-category">
                            <?= icon_embed(APP_ICONS_DIR . 'navigation/' . $category['slug']) ?>
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
                    <button class="button button--bad button--secondary button-shutdown" name="logout">
                        <?= icon_embed('exit') ?>
                        <span>Esci</span>
                    </button>
                </form>
            </div>

            <!-- Mobile open menu icon -->
            <div class="menu__open js-toggle-menu">
                <?= icon_embed('navigation/arrow1-right') ?>
            </div>
        </div>
    </div>
</section>
