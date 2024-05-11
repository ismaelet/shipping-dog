<header class="<?= $header['floating'] ? 'floating' : '' ?>">

	<div id="h-pan">

		<div id="h-bgr" class="bgr"></div>

		<div id="h-bars">

			<!-- Left bar -->
			<div id="h-nav-lef" class="h-bar inlb txt-lef al-mid">

				<!-- Logo -->
				<?php if ($header['logo']) { ?>

					<a href="<?= client_url('/') ?>" id="h-logo" <?= background_image(LOGO_DIR . 'logo.png') ?>></a>

				<?php } ?>

				<!-- Search bar -->
				<?php if ($header['srch-bar']) { ?>

					<form action="<?= client_url('/search') ?>" method="GET" id="h-srch" class="inlb" onClick="$('#h-srch-inp').focus();">

						<input type="checkbox" id="h-srch-cbx" class="hidden">

						<label for="h-srch-cbx" id="h-srch-ico-box" class="stack-items">

							<div class="background fill-all bgr-c1"></div>

							<div class="icon-box">
								<?= embed_icon('search') ?>
							</div>

						</label>

						<div id="h-srch-inp-box">
							<input type="text" id="h-srch-inp" name="search" placeholder="Cerca un prodotto o tag">
						</div>

						<div class="h-name-box inlb al-mid">

							<h3 class="h-name uppercase desk1280">
								<a href="<?= client_url('/') ?>"><?= $header['name'] ?></a>
							</h3>

							<h3 class="h-name uppercase mob1280">
								<a href="<?= client_url('/') ?>"><?= $header['name-mob'] ?></a>
							</h3>

						</div>

					</form>

				<?php } ?>

				<!-- Name -->
				<?php if ($header['name-bar']) { ?>

					<div clas="h-name-box" class="inlb">

						<h3 class="h-name uppercase desk1280">
							<a href="<?= client_url('/') ?>"><?= $header['name'] ?></a>
						</h3>

						<h3 class="h-name uppercase mob1280">
							<a href="<?= client_url('/') ?>"><?= $header['name-mob'] ?></a>
						</h3>

					</div>

				<?php } ?>

			</div>

			<!-- Central bar -->
			<div id="h-nav-cen" class="h-bar inlb text-center al-mid"></div>

			<!-- Right bar -->
			<div id="h-nav-rig" class="h-bar flex gap-6 lg:gap-12">

				<!-- Links -->
				<?php if (!$header['force-mob']) { ?>

					<div class="h-nav-box desk1280 flex gap-12">

						<?php foreach ($header['links'] as $h_link) {

							$h_valid = true;

							//check logged filter
							if (isset($h_link['logged'])) {

								if ($h_link['logged'] && !auth()->check()) $h_valid = false;
								else if (!$h_link['logged'] && auth()->check()) $h_valid = false;
							}

							//check seller filter
							if (isset($h_link['seller'])) {
								if ($h_link['seller'] && !App\Helpers\App::isSeller()) $h_valid = false;
								else if (!$h_link['seller'] && App\Helpers\App::isSeller()) $h_valid = false;
							}

							//check admin filter
							if (isset($h_link['admin']) && !is_admin()) $h_valid = false;

							//check cart filter
							if (isset($h_link['cart']) && !cart_quantity()) $h_valid = false;

							//check mobile filter
							if (isset($h_link['mob'])) $h_valid = false;

							if ($h_valid) { ?>

								<div class="h-link">
									<p class="h-link-txt <?php if (isset($h_link['loc']) && \Legacy\System\Classes\LegacyView::$location == $h_link['loc']) echo 'active' ?>">
										<a href="<?= $h_link['url'] ?>">
											<?= $h_link['title'] ?>
											<?php if (isset($h_link['drop'])) echo ' <span class="h-drop-ico">&#x25BC;</span>' ?>

											<?php if (!empty($h_link['new'])) { ?>

												<span class="h-link__new-icon">
													<?= embed_icon('discount') ?>
												</span>

											<?php } ?>
										</a>
									</p>

									<?php if (isset($h_link['drop'])) { ?>

										<div class="h-drop bgr-c1">
											<?php foreach ($header['dropdowns'][$h_link['drop']] as $h_drop_link) { ?>

												<p class="h-drop-link bgr-wht-h txt-wht txt-blk-h"><a href="<?= $h_drop_link[1] ?>"><?= $h_drop_link[0] ?></a></p>
											<?php } ?>
										</div>

									<?php } ?>
								</div>
						<?php }
						} ?>

					</div>
				<?php } ?>

				<!-- Navigation icons -->
				<div class="h-navigation-icons flex gap-6 items-center">
					<!-- Theme switch -->
					<?php if ($header['light-mode-switcher']) { ?>

						<div class="theme-switch h-icon">
							<div class="light-icon icon-box" data-color-scheme="light">
								<?= embed_icon('sun') ?>
							</div>

							<div class="dark-icon icon-box" data-color-scheme="dark">
								<?= embed_icon('moon') ?>
							</div>
						</div>

					<?php } ?>

					<!-- Favorites icon -->
					<?php if ($header['wishlist'] && \Legacy\System\Classes\LegacyDB::exists('likes WHERE user_id = ?', [auth()->id()])) { ?>

						<a id="h-wish" class="wish icon-box h-link al-mid h-icon" href="<?= client_url('/wishlist') ?>">
							<?= embed_icon('love') ?>
						</a>

					<?php } ?>

					<!-- Cart icon -->
					<a id="h-cart" class="bag icon-box h-link al-mid h-icon" href="<?= client_url('/cart') ?>">

						<?= embed_icon('bag') ?>

						<?php if (cart_quantity()) { ?>
							<h1 id="h-cart-num"><?= cart_quantity() ?></h1>
						<?php } ?>

					</a>
				</div>

				<!-- Login bar -->
				<?php if ($header['login']) { ?>

					<div id="h-log-box" class="<?= $header['force-mob'] ? 'hidden' : 'desk1280' ?>">

						<?php if (auth()->id()) { ?>

							<a href="<?= $header['logged-link'][1] ?>">
								<p class="h-log-txt br-c1-h main"><?= $header['logged-link'][0] ?></p>
							</a>

						<?php } else { ?>

							<a href="<?= $header['login-link'][1] ?>">
								<p class="h-log-txt br-c1-h main"><?= $header['login-link'][0] ?></p>
							</a>

						<?php } ?>

					</div>

				<?php } ?>

				<!-- Mobile menu -->
				<div id="h-mob-pan" class="txt-rig <?php if (!$header['force-mob']) echo 'mob1280' ?>">

					<!-- Mobile icon -->
					<input type="checkbox" id="h-cbxM" class="hidden">

					<label id="h-icoM-box" for="h-cbxM" class="">
						<div id="h-icoM-line1" class="h-icoM-line">
							<div class="h-icoM-rot"></div>
						</div>
						<div id="h-icoM-line2" class="h-icoM-line">
							<div class="h-icoM-rot"></div>
						</div>
						<div id="h-icoM-line3" class="h-icoM-line">
							<div class="h-icoM-rot"></div>
						</div>
					</label>

					<!-- Mobile box -->
					<div id="h-navM" class="txt-lef <?php if (!$header['force-mob']) echo 'mob1280' ?>">

						<div id="h-navM-bgr" class="bgr"></div>

						<div id="h-contM">

							<!-- Links -->
							<?php foreach ($header['links'] as $h_link) {

								$h_valid = true;

								//check logged filter
								if (isset($h_link['logged'])) {

									if ($h_link['logged'] && !auth()->check()) $h_valid = false;
									else if (!$h_link['logged'] && auth()->check()) $h_valid = false;
								}

								//check seller filter
								if (isset($h_link['seller'])) {

									if ($h_link['seller'] && !App\Helpers\App::isSeller()) $h_valid = false;
									else if (!$h_link['seller'] && App\Helpers\App::isSeller()) $h_valid = false;
								}

								//check admin filter
								if (isset($h_link['admin']) && !is_admin()) $h_valid = false;

								//check cart filter
								if (isset($h_link['cart']) && !cart_quantity()) $h_valid = false;

								if ($h_valid) { ?>

									<label class="h-linkM">

										<input type="checkbox" class="h-dropM-cbx hidden">

										<a <?php if (!isset($h_link['drop'])) echo 'href="' . $h_link['url'] . '"' ?>>

											<h3 class="h-linkM-txt uppercase txt-med">

												<span class="h-linkM-bgr bgr bgr-c1"></span>

												<?=
												$h_link['title'];
												if (isset($h_link['drop'])) echo ' <span class="h-dropM-ico">&#x25BC;</span>';
												?>

											</h3>

										</a>


										<?php if (isset($h_link['drop'])) { ?>

											<div class="h-dropM">

												<?php foreach ($header['dropdowns'][$h_link['drop']] as $h_drop_link) { ?>

													<p class="h-dropM-link txt-wht-h bgr-c3-h">
														<a href="<?= $h_drop_link[1] ?>">
															<?= $h_drop_link[0] ?>
														</a>
													</p>

												<?php } ?>
											</div>

										<?php } ?>
									</label>
							<?php }
							} ?>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</header>