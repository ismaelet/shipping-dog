<?php

use function Ecommerce\ecommerce_config;

?>

{% block main %}

<form id="pp_form" class="pp_form" action="https://www.paypal.com/cgi-bin/webscr" method="POST">

	<input type="hidden" name="cmd" value="_cart">
	<input type="hidden" name="business" value="<?= ecommerce_config('paypal-user') ?>">

	<input type="hidden" name="upload" value="1">
	<input type="hidden" name="lc" value="it_IT">
	<input type="hidden" name="currency_code" value="EUR">

	<input type="hidden" name="item_name_1" value="Spese di spedizione">
	<input type="hidden" name="amount_1" value="8.00">
	<input type="hidden" name="quantity_1" value="1">
	<input type="hidden" name="item_number_1" value="ship">

	<input type="hidden" name="custom" value="<?= $id . '&ship&it'; ?>">
</form>

<!-- Load icon -->
<div id="load" class="center-content">
	<div class="load-icon"></div>
</div>

<script>
	document.getElementById('pp_form').submit();
</script>

{% end main %}