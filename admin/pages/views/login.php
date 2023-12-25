{% block main %}

<form class="form grid gap2" method="POST">

	<div class="form-head">
		<h1 class="form-title load ld-bot">Login</h1>
	</div>

	<div class="form-body grid gap1">

		<div class="form-field load ld-lef ld-del1">
			<input type="text" class="inp" name="username" placeholder="<?= __('Username') ?>">
		</div>

		<div class="form-field load ld-rig ld-del1">

			<input type="password" class="inp" name="password" placeholder="<?= __('Password') ?>">

			<?php if ($error) { ?>
				<p class="error">Dati di accesso errati</p>
			<?php } ?>

		</div>

	</div>

	<div class="form-foot load ld-top ld-del1">
		<button type="submit" class="pbtn" name="submit">Log In</button>
	</div>

</form>

{% end main %}