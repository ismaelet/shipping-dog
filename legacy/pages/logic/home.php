<?php

use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Product;
use Legacy\Classes\Ecommerce\Seller;

$productN = 20;
$popularDesignCount = 20;
$sellerCount = 12;
$fanPicsCount = 4;

// productss
$popular = Product::join('stores', 'items.user_id', '=', 'stores.user_id')
	->where('items.status', 1)
	->where('stores.status', 1)
	->orderBy('items.recent_sales', 'desc')
	->limit($productN)
	->get('items.*');

// designs
$popularDesigns = Design::join('stores', 'designs.user_id', '=', 'stores.user_id')
	->where('designs.status', 1)
	->where('stores.status', 1)
	->orderBy('designs.recent_sales', 'desc')
	->limit($popularDesignCount)
	->get('designs.*');

// sellers
$sellers = rows_to_objects(\Legacy\System\Classes\LegacyDB::select(Seller::INDEX . ' FROM ' . Seller::TABLE . ' WHERE status = 1 ORDER BY recent_sales DESC, user_id ASC LIMIT ?', [$sellerCount]), Seller::class);

// customer pics
$client_pics = \Legacy\System\Classes\LegacyDB::select('* FROM home_pics ORDER BY id DESC LIMIT ?', [$fanPicsCount]);

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
