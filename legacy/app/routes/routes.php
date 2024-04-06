<?php

// home
RouteOld::add('/', function () {
	ViewOld::setPage('home')
		->setTemplate('admin')
		->addMiddlewares(['admin'])
		->show();
});
