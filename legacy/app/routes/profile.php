<?php

// profile
RouteOld::add(['/profile', '/profile/*'], function ($path) {
	$page = explode('profile/', $path)[1] ?? 'feed';

	ViewOld::setPage('profile/' . $page)
		->addMiddlewares(['logged'])
		->setTemplate('profile')
		->set('profileLocation', $page)
		->show();
});
