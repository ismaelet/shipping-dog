<?php

use App\View\View;
use View as GlobalView;

?>

<!doctype html>
<html data-theme="<?= $_SESSION['theme-color'] ?>" data-theme-fluid="<?= empty(sys_config('theme-fluid')) ? 0 : 1 ?>" data-client-root="<?= CLIENT_ROOT ?>">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0">
	<meta name="description" content="<?= GlobalView::$description ?>">
	<meta property="og:image" content="<?= GlobalView::$thumbnail ?>">
	<meta property="og:description" content="<?= GlobalView::$description ?>">

	<title><?= GlobalView::$tabTitle ?></title>

	<!-- Styles -->
	<?= StyleLoader::load() . PHP_EOL . PHP_EOL ?>
	<?= View::stackedStyles() ?>

	<!-- Scripts -->
	<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
	<script defer src="https://unpkg.com/axios/dist/axios.min.js"></script>
	<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
	<?= ScriptLoader::load() . PHP_EOL . PHP_EOL ?>
	<?= ScriptLoader::loadHeadInline()  . PHP_EOL . PHP_EOL ?>
	<?= View::stackedScripts() ?>

	<!-- Favicon -->
	<?= favicon() . PHP_EOL ?>
</head>

<body>
	{% place body %}
</body>

<!-- Foot scripts -->
<?= ScriptLoader::loadFootInline() ?>

</html>