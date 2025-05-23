<?php

use App\Legacy\System\DB;

$desc = null;
$dett = null;

$res = DB::select('* FROM items_info WHERE product_id=' . $product->id);

if ($row = $res->fetch_assoc()) {
    $desc = $row['description'];
    $dett = str_replace(['-', "\r\n"], ['&middot;', '<br>'], $row['details']);
    $dett = !$product->isCustom() ? $dett : null;
} elseif (!$product->isCustom()) {
    $desc_tshm_it = $desc_tshm_en = $desc_tshf_it = $desc_tshf_en = "Le nostre t-shirt in puro cotone sono estremamente morbide e confortevoli grazie all'alta densità del tessuto. I capi sono già stati pre-lavati per evitare che si restringano dopo il primo lavaggio.";
    $dett_tshm_it = $dett_tshm_en = $dett_tshf_it = $dett_tshf_en = '&middot; Tessuto: 100% cotone jersey<br>&middot; Densità: 175 g/m² bianco e 185 g/m² colorati<br>&middot; Stampa molto resistente ai lavaggi<br>&middot; Vestibilità normale';

    $desc_vts_it = $desc_vts_en = 'Le nostre t-shirt con scollo a V sono state pensate per la miglior vestibilità possibilie grazie a cuciture laterali sagomate per un taglio femminile. I capi sono già stati pre-lavati per evitare che si restringano dopo il primo lavaggio';
    $dett_vts_it = $dett_vts_en = '&middot; Tessuto: 100% cotone filato Belcoro<br>&middot; Densità: 160 g/m² bianco e 165 g/m² colorati<br>&middot; Stampa molto resistente ai lavaggi<br>&middot; Taglio leggermente allungato';

    $desc_swtm_it = $desc_swtm_en = $desc_swtf_it = $desc_swtf_en = 'Le nostre felpe sono perfette per le mezze stagioni o, portate con una giacca, per il periodo invernale. I capi sono già stati pre-lavati per evitare che si restringano dopo il primo lavaggio.';
    $dett_swtm_it = $dett_swtm_en = $dett_swtf_it = $dett_swtf_en = '&middot; Tessuto: 80% cotone, 20% poliestere<br>&middot; Densità 280 g/m²<br>&middot; Bordo a costine sul girocollo, sui polsini e sulla base';

    $desc_hodm_it = $desc_hodm_en = $desc_hodf_it = $desc_hodf_en = 'Le nostre felpe sono perfette per le mezze stagioni o, portate con una giacca, per il periodo invernale. I capi sono già stati pre-lavati per evitare che si restringano dopo il primo lavaggio.';
    $dett_hodm_it = $dett_hodm_en = $dett_hodf_it = $dett_hodf_en = '&middot; Tessuto: 80% cotone, 20% poliestere<br>&middot; Densità 280 g/m²<br>&middot; Cappuccio foderato con cordoncino in tinta';

    $desc_cho_it = $desc_cho_en = 'Le nostre felpe sono perfette per le mezze stagioni o, portate con una giacca, per il periodo invernale. I capi sono già stati pre-lavati per evitare che si restringano dopo il primo lavaggio.';
    $dett_cho_it = $dett_cho_en = '&middot; <strong>Cappuccio con colore a contrasto</strong><br>&middot; Tessuto: 50% cotone, 50% poliestere<br>&middot; Densità 280 g/m²<br>&middot; Cappuccio foderato con cordoncino';

    $desc_bag_it = $desc_bag_en = "Le nostre sacche sono perfette per viaggi o escursioni all'aria aperta. Realizzate per resistere all'acqua e con bretelle regolabili.";
    $dett_bag_it = $dett_bag_en = "&middot; Tessuto: 100% poliestere<br>&middot; Tessuto resistente all'acqua<br>&middot; Dimensioni: 34 x 45 cm<br>&middot; Capacità: 11 Litri<br>&middot; Bretelle regolabili";

    $desc_jut_it = $desc_jut_en = "Le nostre sacche in juta sono perfette per viaggi o escursioni all'aria aperta. Realizzate interamente in juta senza coloranti, sono l'alternativa naturale allo zainetto in poliestere";
    $dett_jut_it = $dett_jut_en = '&middot; Tessuto: 100% juta<br>&middot; Dimensioni: 30 x 45 cm<br>&middot; Capacità: 8 Litri<br>&middot; Chiusura realizzata a mano in corda robusta';

    $desc_cas_it = $desc_cas_en = 'I nostri astucci multi-uso sono perfetti per contenere penne e accessori per la scuola, oppure da usare in viaggio per portare piccoli oggetti o articoli cosmetici. Sono realizzati completamente in cotone naturale non colorato.';
    $dett_cas_it = $dett_cas_en = '&middot; Tessuto: 100% cotone<br>&middot; Dimensioni: 20 x 12 cm piccolor, 28 x 20 grande<br>&middot; Capacità: 8 Litri<br>&middot; Stile vintage con zip in metallo';

    $desc_epk_it = $desc_epk_en = "I nostri zaini sono perfetti per la scuola o per piccoli viaggi. Dotati di spallacci e schienale imbottiti sono molto comodi da indossare e l'ampia tasca frontale garantisce una capienza maggiore.";
    $dett_epk_it = $dett_epk_en = '&middot; Spallacci regolabili e imbottiti <br>&middot; Schienale imbottito<br>&middot; Dimensioni: 42 x 31 x 21 cm<br>&middot; Capacità: 18 Litri<br>&middot; Tasca frontale con zip';

    $desc_pad_it = $desc_pad_en = 'I nostri mousepad sono realizzati in neoprene per garantire la massima percisione del mouse. Con uno spessore di 0,2 cm risultamo morbidi e scorrevoli, rimanendo piacevoli al tatto anche dopo molte ore di utilizzo continuato.';
    $dett_pad_it = $dett_pad_en = '&middot; Materiale: neoprene con riverstimento in poliestere<br>&middot; Dimensioni: 24,5 x 19 x 0,20 cm<br>&middot; Morbido e scorrevole<br>&middot; Lavabile a basse temperature';

    $desc_tnkm_it = $desc_tnkm_en = $desc_tnkf_it = $desc_tnkf_en = "Le nostre canottiere in puro cotone sono morbide, confortevoli e pensate per l'estate. I capi sono già stati pre-lavati per evitare che si restringano dopo il primo lavaggio.";
    $dett_tnkm_it = $dett_tnkm_en = $dett_tnkf_it = $dett_tnkf_en = '&middot; Tessuto: 100% cotone (grigio melange 90% cotone, 10% poliestere)<br>&middot; Densità: 141 g/m² bianco e 150 g/m² colorati<br>&middot; Costine sulla scollatura e sul giromanica';

    $desc_zipm_it = $desc_zipm_en = $desc_zipf_it = $desc_zipf_en = 'Le nostre felpe sono perfette per le mezze stagioni o, portate con una giacca, per il periodo invernale. I capi sono già stati pre-lavati per evitare che si restringano dopo il primo lavaggio.';
    $dett_zipm_it = $dett_zipm_en = $dett_zipf_it = $dett_zipf_en = '&middot; Tessuto: 80% cotone, 20% poliestere<br>&middot; Densità 271 g/m² colorate, 257 g/m² bianche<br>&middot; Cappuccio con cordoncino';

    $desc_mas_it = $desc_mas_en = "La nostra elegante maschera facciale monodimensionale non è un dispositivo di protezione personale contro le infezioni e non è conforme allo standard FFP, ma ha un'apertura interna per l'inserimento di materiali filtranti o simili. Grazie alle fibbie elastiche e alle piccole freccette sul mento, questa maschera in morbida maglia unica si adatta a qualsiasi forma del viso. ";
    $dett_mas_it = $dett_mas_en = '&middot; Tessuto: 100% cotone<br>&middot; Comodo da indossare e da usare ripetutamente.<br>&middot; Lavabile in lavatrice a 60 °C';

    $desc_lss_it = $desc_lss_en = 'Le nostre maglie a maniche lunghe in puro cotone sono estremamente morbide e confortevoli. I capi sono già stati pre-lavati per evitare che si restringano dopo il primo lavaggio.';
    $dett_lss_it = $dett_lss_en = '&middot; Tessuto: 100% cotone jersey<br>&middot; Densità: 140 g/m² bianco e 169 g/m² colorati<br>&middot; Vestibilità normale';

    $desc = $product->type->info ? $product->type->info->description : null;
    $dett = $product->type->info
        ? implode(
            '<br>',
            array_map(function ($element) {
                return "&middot; $element";
            }, preg_split('/\r\n|\r|\n/', $product->type->info->details)),
        )
        : null;

    $desc = !$desc ? (isset(${'desc_' . $product->type->tag . '_' . 'it'}) ? ${'desc_' . $product->type->tag . '_' . 'it'} : null) : $desc;
    $dett = !$dett ? (isset(${'dett_' . $product->type->tag . '_' . 'it'}) ? ${'dett_' . $product->type->tag . '_' . 'it'} : null) : $dett;
}

?>

@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/product-info.css') }}"
	>
@endpushOnce

<?php if ($dett || $desc) { ?>
<div class="mt-8">
	<?php if ($desc) { ?>
	<h3 class="desc_stitle">Descrizione</h3>
	<p class="desc_txt"><?= $desc ?></p><br>
	<?php } ?>

	<?php if ($dett) { ?>
	<h3 class="desc_stitle">Dettagli prodotto</h3>
	<p class="desc_txt"><?= $dett ?></p>
	<?php } ?>
</div>
<?php } ?>
