<?php

function snippet_binary_icon($value) {
	if ($value) {
		$snippet = '<div class="binary-icon binary-icon_yes icon-box">';
		$snippet .= embed_icon('correct');
	} else {
		$snippet = '<div class="binary-icon binary-icon_no icon-box">';
		$snippet .= embed_icon('close');
	}

	$snippet .= '</div>';

	return $snippet;
}

function snippet_breadcrumbs($breadcrumbs) {
	$snippetParts = [];


	foreach ($breadcrumbs as $name => $link) {
		if ($link) {
			$snippetParts[] = '<a href="' . $link . '">' . $name . '</a>';
		} else {
			$snippetParts[] = $name;
		}
	}

	$snippet = '<p class="breadcrumbs">';
	$snippet .= implode('<span class="breadcrumbs_arrow icon-box">' . embed_icon('navigation/arrow1-right') . '</span>', $snippetParts);
	$snippet .= '</p>';

	return $snippet;
}

function animate_text($text, $speed = 5, $offset = 0, $style = 'bottom') {

	$split = '';

	$text = str_split($text);
	$speed = $speed / 100;
	$offset = $offset / 100;

	if ($style == 'bottom') $class = 'ld-bot';
	else if ($style == 'top') $class = 'ld-top';

	foreach ($text as $i => $char) {

		if ($style == 'mixedV') {
			$seed = rand(1, 2);
			$class = $seed == 1 ? 'ld-bot' : 'ld-top';
		}

		$split .= '<span class="load ' . $class . ' inlb" style="transition-delay: ' . ($speed * $i + $offset) . 's;">' . $char . '</span>';
	}

	return $split;
}

// video with overlay
function playable_video(string $videoUrl, array $parameters = null, array $customClasses = null) {
	$posterUrl = str_replace('.mp4', '-poster.webp', $videoUrl);
	$posterUrl = file_exists(server_path($posterUrl)) ? $posterUrl : false;

	$classes = implode(' ', ['playable-video', 'js-playable-video-wrap', ...$customClasses]);

	return '
		<div class="' . $classes . '">
			<video class="js-playable-video" ' . ($posterUrl ? 'poster="' . client_path($posterUrl) . '"' : '') . '">
				<source src="' . client_path($videoUrl) . '" type="video/mp4">
			</video>
			<div class="playable-video__overlay js-playable-video-overlay">
				<div class="playable-video__play-button">
					' . embed_icon('play') . '
				</div>
			</div>
		</div>';
}

// favicon snippet
function favicon() {

	return '<link rel="apple-touch-icon-precomposed" sizes="57x57" href="' . CLIENT_ROOT . '/app/assets/favicon/apple-touch-icon-57x57.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="' . CLIENT_ROOT . '/app/assets/favicon/apple-touch-icon-114x114.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="' . CLIENT_ROOT . '/app/assets/favicon/apple-touch-icon-72x72.png" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="' . CLIENT_ROOT . '/app/assets/favicon/apple-touch-icon-144x144.png" />
<link rel="apple-touch-icon-precomposed" sizes="60x60" href="' . CLIENT_ROOT . '/app/assets/favicon/apple-touch-icon-60x60.png" />
<link rel="apple-touch-icon-precomposed" sizes="120x120" href="' . CLIENT_ROOT . '/app/assets/favicon/apple-touch-icon-120x120.png" />
<link rel="apple-touch-icon-precomposed" sizes="76x76" href="' . CLIENT_ROOT . '/app/assets/favicon/apple-touch-icon-76x76.png" />
<link rel="apple-touch-icon-precomposed" sizes="152x152" href="' . CLIENT_ROOT . '/app/assets/favicon/apple-touch-icon-152x152.png" />
<link rel="icon" type="image/png" href="' . CLIENT_ROOT . '/app/assets/favicon/favicon-196x196.png" sizes="196x196" />
<link rel="icon" type="image/png" href="' . CLIENT_ROOT . '/app/assets/favicon/favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/png" href="' . CLIENT_ROOT . '/app/assets/favicon/favicon-32x32.png" sizes="32x32" />
<link rel="icon" type="image/png" href="' . CLIENT_ROOT . '/app/assets/favicon/favicon-16x16.png" sizes="16x16" />
<link rel="icon" type="image/png" href="' . CLIENT_ROOT . '/app/assets/favicon/favicon-128.png" sizes="128x128" />';
}

// page head section
function snippet_page_head(string $title = null, bool $contrastingBackground = true) { ?>

	<section class="page-head grid-flow stack-content center-content <?= $contrastingBackground ? 'contrasting-background' : '' ?>">
		<div class="page-head__background fill-all background-fill" <?= background_image('head-background') ?>></div>
		<div class="page-head__content main-wrap">
			<h1 class="page-head__title section-heading"><?= $title ?: \Legacy\System\Classes\LegacyView::$title ?></h1>
		</div>
	</section>

<?php }
