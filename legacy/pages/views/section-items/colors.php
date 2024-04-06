{% block form-body %}

<div class="body">

	<?= generate_form($inputs, false, $error, $done) ?>

</div>

<div class="footer">
	<button type="submit" name="add-color" class="pbtn">Aggiungi il colore</button>
	<button type="submit" name="remove-color" class="sbtn">Rimuovi dai capi selezionati</button>
	<button type="submit" name="remove-color-all" class="sbtn red">Rimuovi rimuovi da tutti i capi</button>
</div>

{% end form-body %}