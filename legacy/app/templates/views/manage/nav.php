<div id="nav" class="">
	<div id="nav-tbl" class="tbl">
		<div id="nav-menu" class="cell">

			<input type="checkbox" id="nav-title-cbx" class="hidden">
			<label id="nav-title-box" class="mob1000" for="nav-title-cbx">

				<h1 id="nav-title" class="txt-11rem txt-cen txt-med txt-upp pad-h-1">
					<span class="btn-icon bgr-c-title" <?= embed_icon('list') ?>></span>
					<span class="btn-txt">Menu Gestione</span>
				</h1>

			</label>

			<div id="nav-list" class="grid gap1 pad-t-2">

				<?php
				foreach ($nav_categories as $category) {

					$cat = $category['category'];

					$cat_active = $this_cat == $cat;

					$pages = ${'nav_' . $cat};

					$pagesN = count($pages) ?>

					<label class="nav-link-lbl" data-num="<?= $pagesN ?>">

						<input type="checkbox" class="nav-cbx hidden <?php if ($cat_active) echo 'active' ?>" <?php if ($cat_active) echo 'checked' ?>>

						<div class="nav-block no-trans">

							<div class="nav-title-box">

								<div class="grid">

									<div class="nav-ico no-trans icon-box">
										<?= embed_icon($cat) ?>
									</div>

									<p class="nav-title no-trans"><?= $category['title'] ?></p>

									<span class="nav-ddown-ico icon-box no-trans">
										<?= embed_icon('navigation/arrow1-down') ?>
									</span>

								</div>

							</div>

							<!-- Dropdown menu -->
							<div class="nav-ddown no-trans <?php if (!$cat_active) echo 'closed' ?>" style="--_count: <?= $pagesN ?>;">

								<?php foreach ($pages as $page) {

									$page_active = false;

									$url_file = $page['file_show'] ?? $page['file'];

									$url_page = empty($url_file) ? '' : '/' . $url_file;

									$url = 'manage/' . $cat . $url_page . '?id=' . $seller->id;

									if (isset($page['file'])) $page_active = $this_page == $page['file'] ?>

									<p class="nav-link <?php if ($cat_active && $page_active) echo 'active' ?>">
										<a href="<?= client_url('/' . $url) ?>">
											<?= $page['title'] ?>
										</a>
									</p>

								<?php } ?>
							</div>
						</div>
					</label>
				<?php } ?>
			</div>
		</div>
	</div>
</div>