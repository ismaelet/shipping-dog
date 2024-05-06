<?php

use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Design\CustomerPhoto;
use App\Models\Ecommerce\Product;
use Legacy\Classes\Ecommerce\Seller;

$productN = 20;
$popularDesignCount = 20;
$sellerCount = 12;
$fanPicsCount = 4;

// products
$popular = Product::join('stores', 'products.user_id', '=', 'stores.user_id')
	->where('products.status', 1)
	->where('stores.status', 1)
	->with('design', 'design.seller', 'design.auth_user_like', 'seller', 'design.logo_group', 'color', 'type', 'position', 'specific_price', 'seller_price', 'base_price')
	->orderBy('products.recent_sales', 'desc')
	->limit($productN)
	->get('products.*');

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
$customerPhotos = CustomerPhoto::inRandomOrder()
	->limit($fanPicsCount)
	->with('design')
	->get();

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
