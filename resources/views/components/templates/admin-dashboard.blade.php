@php

    use App\Models\Admin\AdminCategory;
    use App\Models\Admin\AdminSection;

    $navigationGroups = [];

    // Add the link to the public site
    $publicSiteGroup = [
        [
            'name' => 'Sito pubblico',
            'icon' => 'link',
            'checked' => false,
            'url' => '/',
        ],
    ];

    $navigationGroups[] = $publicSiteGroup;

    // Add the admin links
    $mainGroup = [];
    $dashboardHome = [
        'name' => 'Dashboard',
        'icon' => 'dashboard',
        'checked' => false,
        'url' => '/admin',
    ];

    $mainGroup[] = $dashboardHome;

    // Fetch the current section
    $currentSection = false;
    $currentPathFull = explode('admin', Request::path())[1] ?? '';
    $currentPathFull = ltrim($currentPathFull, '/');

    if ($currentPathFull) {
        $currentCategorySlug = explode('/', $currentPathFull)[0] ?? null;
        $currentSectionSlug = explode('/', $currentPathFull)[1] ?? null;

        $currentSection = AdminSection::where('category', $currentCategorySlug)
            ->where('slug', $currentSectionSlug)
            ->first();
    }

    // Fetch the categories
    $categories = is_supervisor()
        ? AdminCategory::orderBy('manager')->orderBy('sort')->orderBy('name')->get()
        : AdminCategory::where('manager', 0)->orderBy('manager')->orderBy('sort')->orderBy('name')->get();

    foreach ($categories as $categoryRecord) {
        $category = [];
        $category['name'] = $categoryRecord->name;
        $category['icon'] = $categoryRecord->slug;
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

        $mainGroup[] = $category;
    }

    $navigationGroups[] = $mainGroup;
@endphp

<x-templates.dashboard color-scheme="dark" :navigation-groups="$navigationGroups">

    {{ $slot }}

</x-templates.dashboard>
