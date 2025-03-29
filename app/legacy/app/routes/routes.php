<?php

// home
\Legacy\System\Classes\LegacyRoute::add('/', function () {
	\Legacy\System\Classes\LegacyView::setPage('home')
		->setTemplate('admin')
		->addMiddlewares(['admin'])
		->show();
});
