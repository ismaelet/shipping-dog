<?php



?>

{% block main %}

<form id="pp_form" class="pp_form hidden" action="https://www.paypal.com/cgi-bin/webscr" method="POST">

	<input type="hidden" name="cmd" value="_cart">
	<input type="hidden" name="business" value="<?= ecommerce_config('paypal-user') ?>">

	<input type="hidden" name="upload" value="1">
	<input type="hidden" name="lc" value="it_IT">
	<input type="hidden" name="currency_code" value="EUR">

	<input type="hidden" name="item_name_1" value="Spese di spedizione">
	<input type="hidden" name="amount_1" value="8.00">
	<input type="hidden" name="quantity_1" value="1">
	<input type="hidden" name="item_number_1" value="shipping_fee">

	<input type="hidden" name="custom" value="change:<?= $changeRequest->id ?>">
</form>

<!-- Load icon -->
<div class="grid">
	<div class="load-icon"></div>
</div>

<script>
	document.getElementById('pp_form').submit();
</script>

{% end main %}