<?php

// homepage
\Legacy\System\Classes\LegacyRoute::add(['/', '/home'], function () {
	\Legacy\System\Classes\LegacyView::setPage('home')
		->setLocation('home')
		->show();
});

// gallery of client pics
\Legacy\System\Classes\LegacyRoute::add('/fan-pics', function () {
	\Legacy\System\Classes\LegacyView::setPage('fan-pics')
		->setLocation('fan-pics')
		->show();
});

// contacts
\Legacy\System\Classes\LegacyRoute::add('/contacts', function () {
	\Legacy\System\Classes\LegacyView::setPage('infos/contacts')
		->setLocation('info')
		->setTitle('Contatti')
		->show();
});

// faq
\Legacy\System\Classes\LegacyRoute::add('/infos/faq', function () {
	\Legacy\System\Classes\LegacyView::setPage('infos/faq')
		->setTitle('FAQ')
		->setLocation('info')
		->show();
});

// contract
\Legacy\System\Classes\LegacyRoute::add('/infos/contract', function () {
	\Legacy\System\Classes\LegacyView::setPage('infos/contract')
		->setTitle('Condizioni di invio')
		->setTemplate('form')
		->setLocation('info')
		->show();
});

// selling terms
\Legacy\System\Classes\LegacyRoute::add('/infos/terms', function () {
	\Legacy\System\Classes\LegacyView::setPage('infos/terms')
		->setTitle('Condizioni di vendita')
		->setTemplate('form')
		->setLocation('info')
		->show();
});

// privacy
\Legacy\System\Classes\LegacyRoute::add('/infos/privacy', function () {
	\Legacy\System\Classes\LegacyView::setPage('infos/privacy')
		->setTitle('Privacy policy')
		->setTemplate('form')
		->setLocation('info')
		->show();
});
