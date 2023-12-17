<?php

use Ecommerce\Order;
use Ecommerce\Product\Color;
use Ecommerce\Product\Size;

$style = "
/*GENERAL*/
* {padding: 0; position: relative; margin: 0;}

h1, p {font-family: sans-serif;}
h1 {font-variant: small-caps;}

.inp {
	border: 1px solid #CCC;
	width: 100%;
	margin: 5px 0;
	color: #333;
	font-family: Arial;
	font-weight: 100;
	font-size: 1rem;
	box-sizing: border-box;
	padding: 0 10px; line-height: 2.7;
}

body {background-color: #777;}

/*GENERAL PDF*/
main {padding: 0; font-size: .9rem;}

.main-wrap {
	background-color: #FFF;
	width: 800px;
	height: 1131px;
	margin: 0 auto;
}

/*HEAD*/
.list-head {
	background-color: #8495a2;
	padding: 30px 50px;
	overflow: auto;
}

.head_left {float: left;}

.prof_pic {
	display: table-cell;
	background-position: center;
	background-size: contain;
	background-repeat: no-repeat;
	width: 70px;
	height: 70px;
	vertical-align: middle;
}

.head_data {
	display: table-cell;
	padding-right: 25px;
	color: #FFF;
	line-height: 1.8;
	text-align: right;
	vertical-align: middle;
}

.head_right {display: table; float: right;}


.list-title {
	color: rgba(255,255,255,.5);
	left: -2px;
	font-size: 40px;
	font-weight: bold;
	line-height: 1;
}

.head_inv_data {
	margin-top: 10px;
	color: #FFF;
	line-height: 1.8;
	word-spacing: 15px;
}


/*BODY*/
.list-body {
	padding: 0 50px;
}

/*address*/
.ord_addr {
	padding: 50px 0;
	overflow: auto;
}

.addr_block {
	padding-right: 60px;
	float: left;
	max-width: 300px;
}

.addr_block.last {padding-right: 0;}

.ord_stitle {
	margin-bottom: 15px;
	color: #A0ABB4;
}

.addr_inp {
	border-width: 0 0 1px 0 !important;
	box-shadow: none !important;
	
}

.addr_inp:disabled {
	background-color: transparent;
	border: none;
	padding: 0;
	margin: 0;
	line-height: 2;
}

/*items*/
.ord_item-cont {
	display: table;
	table-layout: fixed;
	border-collapse: collapse;
	border-top: 3px solid #8495a2;
	width: 100%;
	box-sizing: border-box;
	margin-top: 100px;
}

.item-row {
	display: table-row;
	border-bottom: 2px solid #EEE;
}

.item-row.first {border: none;}

.item-cell {
	display: table-cell;
	padding: 20px 0 10px 0;
	vertical-align: middle;
	text-align: center;
}

.item-cell.first {text-align: center;}
.item-cell.last {padding-right: 10px;}

#item-col1 {width: 20%;}
#item-col2 {width: 40%;}
#item-col3 {width: 20%;}
#item-col4 {width: 20%;}

.item-col {
	padding: 15px 0 10px 0;
	color: #A0ABB4;
	font-size: .9rem;
}

.item-name {
	display: inline-block;
	padding-bottom: 5px;
	color: #000;
	font-size: 1rem;
	font-family: sharp;
}

.item-grey {
	color: #888; font-size: 1rem;
}

.item-inp {
	border: none !important;
	box-shadow: none !important;
	width: 100%;
	padding: 0;
	margin: 0;
	text-align: center;
	
}

.item-desc {
	color: #333;
	font-size: .9rem;
}

.item-inp:disabled {background-color: transparent;}

.item-cell.first .item-inp {text-align: left;}

/*PAYMENT*/
#bott_panel {
	overflow: auto;
}

.pay_cont {
	width: 50%;
	float: left;
}

.pay_stitle {
	display: inline-block;
	margin-right: 10px;
	color: #555;
	font-size: 1rem;
	vertical-align: top;
}

.pay {
	display: inline-block;
	color: #8495a2;
	font-size: 12px;
	font-weight: normal;
	line-height: 1.6;
	margin-bottom: 20px;
	vertical-align: top;
}

/*TOTAL*/
.total_cont {
	width: 50%;
	text-align: right;
	float: left;
}

.tot_row {
	padding-bottom: 10px;
}

.tot_txt {
	display: inline;
	color: #8495a2;
}

.tot_numb {
	display: inline-block;
	width: 150px;
	font-size: 1rem;
}

.tot_row.last {
	font-size: 1rem;
}

#download {
	display: block;
	background-color: #ebebeb;
	border: 1px solid #DDD;
	padding: 5px 20px;
	margin: 80px auto 0 auto;
	color: #333;
}";
	
$row_addr = DB::row('* FROM addresses WHERE cartID=?', [$id]);
$region = empty($row_addr['region']) ? '' : ' ('.$row_addr['region'].')';

$res_ord = DB::select('* FROM refunds_ord WHERE cartID=?', [$id]);

$product_rows = '';

while ($row_sel = $res_ord->fetch_assoc()) {

	$order = new Order($row_sel['ordID'], '*');
	$product = $order->product;
	$new_size = $row_sel['size_new'] == $row_sel['size_old'] ? '-' : (new Size($row_sel['size_new']))->name;
	$new_color = $row_sel['color_new'] == $row_sel['color_old'] ? '-' : (new Color($row_sel['color_new']))->name;
	
	$product_rows .= '
		<div class="item-row">
			<div class="item-cell item-data">
				<input type="text" class="inp item-inp" value="'.$order->id.'" disabled>
			</div>

			<div class="item-cell first">
				<input type="text" class="inp item-inp item-desc" value="'.$order->desc['normal'].'">
			</div>
			<div class="item-cell"><input type="text" class="inp item-inp" value="'.$new_size.'"></div>
			<div class="item-cell last"><input type="text" class="inp item-inp" value="'.$new_color.'"></div>
		</div>'
	;
}

$body = '
<div class="main-wrap">
	<div class="list">

		<div class="list-head">
			<div class="head_left">
				<h1 class="list-title bgr-c1 txt-wht">RESO</h1>
				<p class="head_inv_data">
					Data: '.date("d/m/Y").'<br>
					Reso#: '.$id.'
				</p>
			</div>

			<div class="head_right">
				<p class="head_data">
					Shipping Dog SNC<br>
					'.sys_config('contact-email').'<br>
					Piazza Scopoli, 9<br>
					38033 Cavalese (TN)
				</p>
				<div class="prof_pic" style="background-image: url(http://shippingdog.com/app/assets/logo/logo_white.png);"></div>
			</div>
		</div>

		<form class="list-body" method="POST">

			<div class="ord_cont">
				<div class="box">
					<div class="ord_body">

						<div class="ord_addr">
							<div class="addr_block">
								<p class="ord_stitle">Indirizzo di provenienza</p>

								<input type="text" class="inp addr_inp wide" value="'.$row_addr['aname'].'" disabled>
								<input type="text" class="inp addr_inp wide" value="'.$row_addr['address'].'" disabled>
								<input type="text" class="inp addr_inp wide" value="'.$row_addr['zip'].' '.$row_addr['city'].$region.'" disabled>
							</div>
							
							<div class="addr_block last">
								<p class="ord_stitle">Indirizzo di spedizione</p>

								<input type="text" class="inp addr_inp wide" value="Shipping Dog SNC" disabled>
								<input type="text" class="inp addr_inp wide" value="Piazza Scopoli, 9" disabled>
								<input type="text" class="inp addr_inp wide" value="38033 Cavalese (TN)" disabled>
							</div>
						</div>

						<!--ITEMS-->
						<div class="ord_item-cont">
							<div class="item-row first">
								<p id="item-col1" class="item-cell item-col first">ID</p>
								<p id="item-col2" class="item-cell item-col">Articolo</p>
								<p id="item-col3" class="item-cell item-col">Nuova taglia</p>
								<p id="item-col4" class="item-cell item-col last">Nuovo colore</p>
							</div>
							
							'.$product_rows.'
							
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>';

$html = "
<html data-theme="<?= $_SESSION['theme-color'] ?? 'light' ?>">
	<head>
		<meta charset='UTF-8'>
		<style>".$style."</style>
	</head>
	".$body."
</HTML>";

//save PDF data
$name = 'Refund_'.$id.'.pdf';

$pdf_data = array(
	"source_type" => 'html',
	"source" => $html,
	"page_size" => 'A4',
	"margin"=>array("right"=>"0","left"=>'0',"top"=>"0","bottom"=>"0"),
	"action" => 'save',
	"save_directory" => SERVER_ROOT.'/uploads/documents/refunds',
	"file_name" => $name
);

//generate pdf
//echo $html;
phptopdf($pdf_data);