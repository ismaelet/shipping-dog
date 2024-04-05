<?php

use ViewManager\Ecommerce\CartList;

?>

{% block content %}

<form class="form" method="POST">

	<h1 class="stitle">LISTA ORDINI</h1>

	<div class="form-body pad-b-2">
		<?= (new CartList())->render() ?>
	</div>

</form>

{% end content %}