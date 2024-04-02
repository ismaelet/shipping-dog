{% block main %}

<form class="page-content" method="POST">
	<div class="main-wrap grid-flow fa-center" data-spacing="700">
		<h1 class="title">Login</h1>

		<div class="grid-flow" data-spacing="400">
			<div class="form-field">
				<input type="text" name="username" placeholder="<?= __('Username') ?>">
			</div>

			<div class="form-field">
				<input type="password" name="password" placeholder="<?= __('Password') ?>">

				<?php if ($error) { ?>
					<p class="error">Dati di accesso errati</p>
				<?php } ?>
			</div>
		</div>

		<button type="submit" class="button button--full-width" name="submit">Log In</button>
	</div>
</form>

{% end main %}