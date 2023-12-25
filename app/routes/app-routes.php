<?php

// homepage
Route::add('/', function () {
	View::setPage('home')
		->setLocation('home')
		->show();
});

// gallery of client pics
Route::add('/fan-pics', function () {
	View::setPage('fan-pics')
		->setLocation('fan-pics')
		->show();
});

// contacts
Route::add('/contacts', function () {
	View::setPage('infos/contacts')
		->setLocation('info')
		->setTitle('Contatti')
		->show();
});

// faq
Route::add('/infos/faq', function () {
	View::setPage('infos/faq')
		->setTitle('FAQ')
		->setLocation('info')
		->show();
});

// contract
Route::add('/infos/contract', function () {
	View::setPage('infos/contract')
		->setTitle('Condizioni di invio')
		->setTemplate('form')
		->setLocation('info')
		->show();
});

// selling terms
Route::add('/infos/terms', function () {
	View::setPage('infos/terms')
		->setTitle('Condizioni di vendita')
		->setTemplate('form')
		->setLocation('info')
		->show();
});

// privacy
Route::add('/infos/privacy', function () {
	View::setPage('infos/privacy')
		->setTitle('Privacy policy')
		->setTemplate('form')
		->setLocation('info')
		->show();
});
