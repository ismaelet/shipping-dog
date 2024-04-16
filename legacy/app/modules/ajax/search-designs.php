<?php

$full = false;

include(substr(__DIR__, 0, strpos(__DIR__, 'app')) . '/system/setup/setup.php');

//search
if (!empty($_POST['search'])) {

	$filter = empty($_POST['filter']) ? '' : 'AND ' . $_POST['filter'];

	$res = DBOld::select("ID, name FROM designs WHERE id = ? OR name LIKE CONCAT('%',?,'%') " . $filter . " ORDER BY sales DESC LIMIT 0,6", [$_POST['search'], $_POST['search']]);

	while ($row = $res->fetch_assoc()) { ?>

		<p class="ajax-search-item" data-id="<?= $row['id']; ?>" data-txt="<?= $row['name']; ?>"><?= $row['name']; ?></p>

<?php }
}
