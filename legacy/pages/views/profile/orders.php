<?php

use Legacy\Classes\ViewManager\Ecommerce\OrderList;

?>

{% block content %}

<form class="form" method="POST">

	<h1 class="stitle">LISTA ORDINI</h1>

	<div class="form-body pad-b-2">
		<?= (new OrderList())->render() ?>
	</div>

</form>

{% end content %}