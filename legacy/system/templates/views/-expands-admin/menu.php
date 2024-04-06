<section class="menu js-menu">
	<div class="menu__content">

		<div class="">
			<!-- Title -->
			<div class="menu__header" href="/admin">
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
						<a class="menu-item__header" href="/admin">
							<?= embed_icon('home') ?>
							<p class="menu-item__title">Home</p>
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
					<button class="button button--bad button--secondary button-shutdown" name="logout">
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