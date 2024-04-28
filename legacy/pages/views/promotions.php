<?php

use Legacy\Classes\Ecommerce\PromotionShop;

?>

{% block main %}

<div class="page-content main-wrap">
	<?= (new PromotionShop())->render() ?>
</div>

{% end main %}