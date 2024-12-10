{% block main %}

<input type="hidden" id="type-id" data-id="<?php echo $product->type_id ?>" />

<?php

include_once('-expands-product/data.php');
include_once('-expands-product/tags.php');
include_once('-expands-product/other-types.php');
include_once('-expands-product/related-products.php');

?>

{% end main %}