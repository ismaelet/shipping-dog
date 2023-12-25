<section class="menu js-menu">
	<div class="menu__content">

		<div class="js-open-menu">
			<!-- Title -->
			<div class="menu__header" href="<?= PUBLIC_CLIENT_URL ?>">
				<?= $navigationLogo ?>
				<h1 class="menu__title"><?= sys_config('name') ?></h1>
			</div>

			<!-- Admin sections -->
			<div class="menu__group">

				<!-- Home link -->
				<div class="menu-item">
					<a class="menu-item__header" href="<?= client_url('/') ?>">
						<?= icon_embed('home') ?>
						<p class="menu-item__title">Home</p>
					</a>
				</div>

				<!-- Navigation -->
				<?php foreach ($menu['categories'] as $category) { ?>

					<!-- Menu category -->
					<div class="menu-item <?= $category['checked'] ? 'active' : '' ?>">

						<!-- Item header -->
						<div class="menu-item__header js-open-menu-item">
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

			</div>
		</div>

		<!-- Mobile open menu icon -->
		<div class="menu__open js-toggle-menu">
			<?= icon_embed('navigation/arrow1-right') ?>
		</div>
	</div>
</section>