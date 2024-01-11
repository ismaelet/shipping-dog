<?php
$classes = isset($classes) ? implode(' ', ['button', ...$classes]) : 'button';
$type = 'submit';
?>

<template>

	<?php if (!empty($href)) { ?>

		<a href="<?= $href ?>" class="<?= $classes ?>"><?= $label ?></a>

	<?php } else { ?>

		<button type="<?= $type ?>" class="<?= $classes ?>"><?= $label ?></button>

	<?php } ?>

</template>

<style>

</style>