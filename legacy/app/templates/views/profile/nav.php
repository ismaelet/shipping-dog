<section id="nav" class="cell">

	<div id="nav-box">

		<input type="checkbox" id="nav-cbx" class="hidden">

		<!-- Title -->
		<label id="nav-title-box" for="nav-cbx">

			<h3 id="nav-title" class="txt-11rem txt-upp flex items-center pad-v-1 pad-h-1">
				<span class="btn-icon"><?= embed_icon('list') ?></span>
				<span class="btn-txt">Profilo Personale</span>
			</h3>

		</label>

		<!-- Navigation menu -->
		<nav id="nav-list">

			<a href="<?= client_url('/profile') ?>">
				<p class="nav-link pad-l-1 <?= $profileLocation == 'feed' ? 'active' : '' ?>">Feed</p>
			</a>

			<a href="<?= client_url('/profile/data') ?>">
				<p class="nav-link pad-l-1 <?= $profileLocation == 'data' ? 'active' : '' ?>">Modifica dati</p>
			</a>

			<a href="<?= route('user-dashboard.orders') ?>">
				<p class="nav-link pad-l-1 <?= $profileLocation == 'orders' ? 'active' : '' ?>">Lista ordini</p>
			</a>

			<?php if (App\Helpers\App::isSeller()) { ?>

				<a href="<?= client_url('/manage') ?>">
					<p class="nav-link pad-l-1 <?= $profileLocation == 'tools' ? 'active' : '' ?>">Il mio negozio</p>
				</a>

			<?php } ?>

			<a href="<?= client_url('/logout') ?>">
				<p class="nav-link pad-l-1 <?= $profileLocation == 'logout' ? 'active' : '' ?>">Logout</p>
			</a>

		</nav>

	</div>

</section>