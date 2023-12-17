{% block form-footer %}

<?php if ($cartCount) { ?>
	<button type="submit" name="submit" class="button" onclick="return confirm('Sei sicuro di voler generare le etichette?')">Scarica le nuova etichette</button>
<?php } ?>

<a href="<?= client_url('/uploads/documents/Etichette-Corriere.xlsx') ?>" class="button button--secondary">
	Riscarica le ultime etichette
</a>

{% end form-footer %}