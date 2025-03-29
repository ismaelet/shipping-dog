<?php

use Legacy\View\View;

$colorScheme = (isset($_COOKIE['color-scheme']) && in_array($_COOKIE['color-scheme'], ['light', 'dark'])
	? $_COOKIE['color-scheme']
	: config('custom.meta_data.default_color_scheme'));

?>

<!doctype html>
<html class="legacy-website" data-color-scheme="<?= $colorScheme ?>" data-theme-fluid="<?= empty(sys_config('theme-fluid')) ? 0 : 1 ?>" data-client-root="/legacy">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0">
	<meta name="description" content="<?= \Legacy\System\Classes\LegacyView::$description ?>">
	<meta property="og:image" content="<?= \Legacy\System\Classes\LegacyView::$thumbnail ?>">
	<meta property="og:description" content="<?= \Legacy\System\Classes\LegacyView::$description ?>">

	<title><?= \Legacy\System\Classes\LegacyView::$tabTitle ?></title>

	<!-- Styles -->
	<?= \Legacy\System\Classes\StyleLoader::load() . PHP_EOL . PHP_EOL ?>
	<?= View::stackedStyles() ?>

	<!-- Scripts -->
	<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
	<script defer src="https://unpkg.com/axios/dist/axios.min.js"></script>
	<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
	<?= \Legacy\System\Classes\ScriptLoader::load() . PHP_EOL . PHP_EOL ?>
	<?= \Legacy\System\Classes\ScriptLoader::loadHeadInline()  . PHP_EOL . PHP_EOL ?>
	<?= View::stackedScripts() ?>

	<!-- Favicon -->
	<link rel="icon" href="<?= asset('favicon.ico') ?>">
</head>

<body>
	{% place body %}

	<?php if (sys_config('cookies-warning') && !isset($_COOKIE['cookies-warning'])) { ?>

		<div class="cookies-warning">
			<h3 class="cookies-warning_title">Avviso cookies</h3>
			<div class="cookies-warning_body">
				<p class="cookies-warning_text">
					Questo sito fa uso di cookie per migliorare l’esperienza di navigazione degli utenti e per raccogliere informazioni sull’utilizzo del sito stesso. Utilizziamo sia cookie tecnici sia cookie di parti terze per inviare messaggi promozionali sulla base dei comportamenti degli utenti. Può conoscere i dettagli consultando la nostra privacy policy <a class="cookies-warning_link" href="<?= client_url('/privacy') ?>">qui</a>. Proseguendo nella navigazione si accetta l’uso dei cookie; in caso contrario è possibile abbandonare il sito.
				</p>
				<div class="cookies-warning_footer">
					<div class="cookies-warning_pbtn cookies-warning_btn pbtn js-accept-cookies-warning">Accetta</div>
					<!-- <div class="cookies-warning_sbtn cookies-warning_btn sbtn">Rifiuta</div> -->
				</div>
			</div>
		</div>

	<?php } ?>
</body>

<!-- Foot scripts -->
<?= \Legacy\System\Classes\ScriptLoader::loadFootInline() ?>

</html>