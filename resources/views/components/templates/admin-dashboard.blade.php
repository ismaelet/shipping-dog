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

        $currentSection = AdminSection::join(
            'admin_categories',
            'admin_sections.category_id',
            '=',
            'admin_categories.id',
        )
            ->where('admin_categories.slug', $currentCategorySlug)
            ->where('admin_sections.slug', $currentSectionSlug)
            ->first();
    }

    // Fetch the categories
    $categories = is_supervisor()
        ? AdminCategory::orderBy('for_supervisors')->orderBy('order')->orderBy('name')->get()
        : AdminCategory::where('for_supervisors', 0)
            ->orderBy('for_supervisors')
            ->orderBy('order')
            ->orderBy('name')
            ->get();

    foreach ($categories as $categoryRecord) {
        $category = [];
        $category['name'] = $categoryRecord->name;
        $category['icon'] = $categoryRecord->slug;
        $category['slug'] = $categoryRecord->slug;
        $category['checked'] = $currentSection && $category['slug'] == $currentSection->category->slug;

        $sections = is_supervisor()
            ? AdminSection::where('category_id', $categoryRecord->id)
                ->where('hidden', 0)
                ->orderBy('order')
                ->orderBy('name')
                ->get()
            : AdminSection::where('category_id', $categoryRecord->id)
                ->where('for_supervisors', 0)
                ->where('hidden', 0)
                ->orderBy('order')
                ->orderBy('name')
                ->get();

        foreach ($sections as $sectionRecord) {
            $section = [];
            $section['name'] = $sectionRecord->name;
            $section['checked'] =
                $currentSection && $category['checked'] && $sectionRecord->slug == $currentSection->slug;
            $section['url'] = $sectionRecord
                ? '/admin/' . $sectionRecord->category->slug . '/' . $sectionRecord->slug
                : null;

            $category['sections'][] = $section;
        }

        $mainGroup[] = $category;
    }

    $navigationGroups[] = $mainGroup;
@endphp

@pushOnce('styles')
    <style>
        .button {
            border-radius: var(--border-radius-400);
        }
    </style>
@endpushOnce

<x-templates.dashboard color-scheme="{{ config('custom.meta_data.default_color_scheme') }}" :navigation-groups="$navigationGroups">

    {{ $slot }}

</x-templates.dashboard>
