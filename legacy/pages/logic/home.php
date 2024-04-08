<?php

use App\Models\Ecommerce\Design;
use Ecommerce\Design as OldDesign;
use Ecommerce\Seller;

$productN = 20;
$popularDesignCount = 20;
$sellerCount = 12;
$fanPicsCount = 4;

// productss
$popular = DBOld::select('ID FROM items INNER JOIN stores ON items.userID = stores.userID WHERE items.status = 1 AND stores.status = 1 ORDER BY items.rsales DESC LIMIT ?', [$productN]);

// designs
$popularDesigns = Design::join('stores', 'designs.userID', '=', 'stores.userID')
	->where('designs.status', 1)
	->where('stores.status', 1)
	->orderBy('designs.rsales', 'desc')
	->limit($popularDesignCount)
	->get();

// sellers
$sellers = rows_to_objects(DBOld::select(Seller::INDEX . ' FROM ' . Seller::TABLE . ' WHERE status = 1 ORDER BY rsales DESC, userID ASC LIMIT ?', [$sellerCount]), 'Ecommerce\Seller');

// customer pics
$client_pics = DBOld::select('* FROM home_pics ORDER BY ID DESC LIMIT ?', [$fanPicsCount]);

// categories
$categories = [
	['Abbigliamento Donna', 'Tantissimi colori disponibili', 'shop?gender=f'],
	['Felpe & Hoodie', 'Calde e confortevoli', 'shop?type=hodf'],
	['Abbigliamento<br>Uomo', 'T-shirt, canotte e molto altro', 'shop?gender=m'],
	['Zaini & Accessori', 'Sacche, astucci, maschere...', 'shop?group=m'],
	['Mouse Pad', '', 'shop?type=pad'],
];

$features = [
	[
		"Spedizioni rapide in Italia",
		"Riceverai il tuo ordine entro 24 ore dal momento della spedizione in Italia, 48 ore per le isole."
	],
	[
		"Nuovi sconti ogni mese",
		"Ogni mese troverai offerte per i prodotti selezionati o per tutti gli articoli presenti nel nostro shop."
	],
	[
		"Servizio di cambio taglia e rimborso",
		"Voi cambiare taglia? Basta seguire la nostra procedura online, facile e veloce."
	],
];
