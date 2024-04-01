<?php
$type = $type ?? 'text';
$name = $name ?? null;
$value = $value ?? null;
$placeholder = $placeholder ?? null;
$required = $required ?? false;
$disabled = $disabled ?? false;
$readonly = $readonly ?? false;
$trim = $trim ?? false;
?>

<template>
	<input type="<?= $type ?>" class="<?= $trim ? 'js-input-trim' : '' ?>" <?= $name ? 'name="' . $name . '"' : '' ?> <?= $value ? 'value="' . $value . '"' : '' ?> <?= $placeholder ? 'placeholder="' . $placeholder . '"' : '' ?> <?= $required ? 'required' : '' ?> <?= $disabled ? 'disabled' : '' ?> <?= $readonly ? 'readonly' : '' ?> />
</template>

<style>

</style>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		document.querySelectorAll('.js-input-trim').forEach((element) => {
			element.addEventListener('blur', function() {
				this.value = this.value.trim();
			});
		});
	});
</script>