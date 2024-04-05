<?php

// login
RouteOld::add('/login', function () {
	ViewOld::setPage('access/login')
		->setTitle('Login')
		->setMiddlewares(['not-admin'])
		->setTemplate('void')
		->show();
});

// logout
RouteOld::add('/logout', function () {
	ViewOld::setPage('access/logout')
		->setTitle('Logout')
		->setMiddlewares(['admin'])
		->setTemplate('void')
		->show();
});
