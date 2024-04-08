<?php

use Illuminate\Support\Facades\Auth;

?>

{% block content %}

<div class="grid gap2">
	<form class="form form-template" method="POST">
		<div id="data-anch" class="anchor"></div>

		<h2 class="stitle">Dati profilo</h2>

		<div class="body">

			<?php if ($done == 'data') { ?>

				<p class="confirmation">Dati modificati con successo</p>

			<?php } ?>

			<p class="lbl">Username:</p>
			<input type="text" class="input inp-nospace" name="username" value="<?= Auth::user()->username; ?>" placeholder="Username" maxlength="16" required>

			<?php if ($error == 'user_match') { ?>

				<p class="error">L'username scelto è già associato ad un altro profilo</p>

			<?php } ?>

			<p class="lbl">Nome e cognome:</p>
			<input type="text" class="input" name="fname" value="<?= Auth::user()->fname; ?>" placeholder="Nome">
			<input type="text" class="input" name="lname" value="<?= Auth::user()->lname; ?>" placeholder="Cognome">

			<p class="lbl">E-mail:</p>
			<input type="email" class="input" name="email" value="<?= Auth::user()->email; ?>" placeholder="email" required>

			<?php if ($error == 'email_match') { ?>

				<p class="error">L'email scelta è già associata ad un altro profilo</p>

			<?php } ?>

		</div>

		<div class="footer">
			<button type="submit" name="edit_data" class="btn button--full-width">Modifica</button>
		</div>
	</form>

	<form class="form form-template" method="POST">
		<div id="pass-anch" class="anchor"></div>
		<h2 class="stitle">Password</h2>
		<div class="body">

			<?php if ($done == 'pass') { ?>

				<p class="confirmation">Password modificata con successo</p>

			<?php } ?>

			<p class="lbl">Password:</p>

			<input type="password" class="input" name="passOld" placeholder="Password attuale" maxlength="16" required>

			<?php if ($error == 'pass_bad') { ?>

				<p class="error">La password inserita non corrisponde alla password attuale</p>

			<?php } ?>

			<input type="password" class="input" name="passNew" placeholder="Nuova password" maxlength="16" required>
			<input type="password" class="input" name="passConf" placeholder="Conferma nuova password" maxlength="16" required>

			<?php if ($error == 'pass_match') { ?>

				<p class="error">La nuova password e la conferma non corrispondono</p>

			<?php } ?>

		</div>

		<div class="footer">
			<button type="submit" name="edit_pass" class="btn button--full-width">Modifica</button>
		</div>
	</form>

	<form class="form form-template file-form" method="POST" enctype="multipart/form-data">
		<h2 class="stitle">Immagine profilo</h2>

		<div class="body">
			<p class="lbl">Modifica l'immagine profilo:</p>
			<input type="file" name="file" class="input file-inp">
			<button type="submit" name="edit_icon" class="file-submit hidden"></button>
			<div class="file-load"></div>
		</div>
	</form>

	<form class="form form-template" method="POST">
		<h2 class="stitle">Elimina l'account</h2>

		<div class="body">
			<p>Clicca qui sotto se vuoi cancellare definitivamente il tuo account. <strong>L'operazione NON può essere annullata.</strong></p>
		</div>

		<div class="footer">
			<button type="submit" name="delete-account" class="btn button--warning button--full-width" onclick="return confirm('Sei sicuro di voler eliminare l\'account? Non potrà essere ripristinato.')">
				<?= embed_icon('delete') ?>
				Elimina l'account
			</button>
		</div>
	</form>
</div>

{% end content %}