@use('App\Models\Admin\AdminCategory')
@use('App\Models\Admin\AdminSection')

@php
	$navigationGroups = [];

	// Add the link to the public site
	$publicSiteGroup = [
	    [
	        'name' => 'Dashboard Nuova',
	        'icon' => 'link',
	        'checked' => false,
	        'url' => '/admin',
	    ],
	    [
	        'name' => 'Sito pubblico',
	        'icon' => 'link',
	        'checked' => false,
	        'url' => route('home'),
	    ],
	];

	$navigationGroups[] = $publicSiteGroup;

	// Add the admin links
	$mainGroup = [];
	$dashboardHome = [
	    'name' => 'Dashboard',
	    'icon' => 'dashboard',
	    'checked' => false,
	    'url' => route('legacy-admin.dashboard'),
	];

	$mainGroup[] = $dashboardHome;

	// Fetch the categories
	$categories = is_supervisor()
	    ? AdminCategory::orderBy('for_supervisors')->orderBy('order')->orderBy('name')->with('sections')->get()
	    : AdminCategory::where('for_supervisors', false)
	        ->orderBy('for_supervisors')
	        ->orderBy('order')
	        ->orderBy('name')
	        ->with('sections')
	        ->get();

	foreach ($categories as $categoryRecord) {
	    $category = [];
	    $category['name'] = $categoryRecord->name;
	    $category['icon'] = $categoryRecord->slug;
	    $category['slug'] = $categoryRecord->slug;

	    $sections = is_supervisor()
	        ? $categoryRecord->sections
	            ->where('hidden', false)
	            ->sortBy('order')
	            ->sortByDesc('resource')
	            ->sortBy('action')
	            ->sortBy('name')
	        : $categoryRecord->sections
	            ->where('for_supervisors', false)
	            ->where('hidden', false)
	            ->sortBy('order')
	            ->sortByDesc('resource')
	            ->sortBy('action')
	            ->sortBy('name');

	    foreach ($sections as $sectionRecord) {
	        $section = [];
	        $section['name'] = $sectionRecord->name;
	        $section['url'] = $sectionRecord->url();
	        $section['route_name'] = $sectionRecord->routeName();

	        $category['items'][] = $section;
	    }

	    $mainGroup[] = $category;
	}

	$navigationGroups[] = $mainGroup;

	// Add the link to log out
	$publicSiteGroup = [
	    [
	        'name' => 'Logout',
	        'icon' => 'logout',
	        'checked' => false,
	        'url' => route('logout'),
	    ],
	];

	$navigationGroups[] = $publicSiteGroup;
@endphp

<x-layouts.empty>
	<x-dashboard :$navigationGroups>

		{{ $slot }}

	</x-dashboard>
</x-layouts.empty>
