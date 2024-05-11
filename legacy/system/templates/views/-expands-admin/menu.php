<?php

use App\Models\Admin\AdminCategory;
use App\Models\Admin\AdminSection;

$currentSection = false;

$currentRoute = explode('admin/', $_SERVER['REQUEST_URI'], 2)[1] ?? null;
$currentRoute = explode('/', $currentRoute);

// fetch categories
$menu['categories'] = [];
$queryManager = is_supervisor() ? '' : 'for_supervisors=0';

$categories = $queryManager ?
	AdminCategory::where($queryManager)->orderBy('for_supervisors')->orderBy('order')->orderBy('name')->get() :
	AdminCategory::orderBy('for_supervisors')->orderBy('order')->orderBy('name')->get();

foreach ($categories as $categoryData) {
	$category = [];
	$category['name'] = $categoryData->name;
	$category['slug'] = $categoryData->slug;
	$category['checked'] = $currentSection && $category['slug'] == $currentSection['category'];

	$sections = $queryManager ?
		AdminSection::where('category_id', $categoryData->id)->where($queryManager)->orderBy('order')->orderBy('name')->get() :
		AdminSection::where('category_id', $categoryData->id)->orderBy('order')->orderBy('name')->get();

	foreach ($sections as $sectionData) {
		if (!$sectionData->hidden && $sectionData->category) {
			$section = [];
			$section['name'] = $sectionData->name;
			$section['checked'] = $currentSection && $category['checked'] && ($sectionData->slug == $currentSection['slug'] || ($sectionData->hidden && str_replace('add', 'list',  $currentSection['slug'])));
			$section['url'] = $sectionData->url();

			$category['sections'][] = $section;
		}
	}

	$menu['categories'][] = $category;
}

$navigationLogo = embed_icon(LOGO_DIR . 'logo', ['menu__logo']);
?>

<section class="menu js-menu">
	<div class="menu__content">

		<div class="">
			<!-- Title -->
			<div class="menu__header" href="/admin/dashboard">
				<?= $navigationLogo ?>
				<h1 class="menu__title"><?= sys_config('name') ?></h1>
			</div>

			<!-- Admin sections -->
			<div class="menu__groups grid-flow js-open-menu" data-spacing="400">
				<div class="menu__group">
					<!-- Home link -->
					<div class="menu-item">
						<a class="menu-item__header" href="/" target="_blank">
							<?= embed_icon('link') ?>
							<p class="menu-item__title">Sito pubblico</p>
						</a>
					</div>
				</div>

				<span class="menu__divider"></span>

				<div class="menu__group">

					<!-- Home link -->
					<div class="menu-item">
						<a class="menu-item__header" href="/admin/dashboard">
							<?= embed_icon('dashboard') ?>
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

				<form class="menu__group flex center-content" method="POST">
					<button class="btn button--bad btn--secondary button-shutdown" name="logout">
						<?= embed_icon('exit') ?>
						<span>Esci</span>
					</button>
				</form>
			</div>

			<!-- Mobile open menu icon -->
			<div class="menu__open js-toggle-menu">
				<?= embed_icon('navigation/arrow1-right') ?>
			</div>
		</div>
	</div>
</section>