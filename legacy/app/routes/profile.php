<?php

// profile
\Legacy\System\Classes\LegacyRoute::add(['/profile', '/profile/*'], function ($path) {
	$page = explode('profile/', $path)[1] ?? 'feed';

	\Legacy\System\Classes\LegacyView::setPage('profile/' . $page)
		->addMiddlewares(['logged'])
		->setTemplate('profile')
		->set('profileLocation', $page)
		->show();
});
