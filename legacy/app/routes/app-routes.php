<?php

// homepage
RouteOld::add(['/', '/home'], function () {
	ViewOld::setPage('home')
		->setLocation('home')
		->show();
});

// gallery of client pics
RouteOld::add('/fan-pics', function () {
	ViewOld::setPage('fan-pics')
		->setLocation('fan-pics')
		->show();
});

// contacts
RouteOld::add('/contacts', function () {
	ViewOld::setPage('infos/contacts')
		->setLocation('info')
		->setTitle('Contatti')
		->show();
});

// faq
RouteOld::add('/infos/faq', function () {
	ViewOld::setPage('infos/faq')
		->setTitle('FAQ')
		->setLocation('info')
		->show();
});

// contract
RouteOld::add('/infos/contract', function () {
	ViewOld::setPage('infos/contract')
		->setTitle('Condizioni di invio')
		->setTemplate('form')
		->setLocation('info')
		->show();
});

// selling terms
RouteOld::add('/infos/terms', function () {
	ViewOld::setPage('infos/terms')
		->setTitle('Condizioni di vendita')
		->setTemplate('form')
		->setLocation('info')
		->show();
});

// privacy
RouteOld::add('/infos/privacy', function () {
	ViewOld::setPage('infos/privacy')
		->setTitle('Privacy policy')
		->setTemplate('form')
		->setLocation('info')
		->show();
});
