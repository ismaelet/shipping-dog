<?php

// profile
Route::add(['/profile', '/profile/*'], function ($path) {
	$page = explode('profile/', $path)[1] ?? 'feed';

	View::setPage('profile/' . $page)
		->addMiddlewares(['logged'])
		->setTemplate('profile')
		->set('profileLocation', $page)
		->show();
});
