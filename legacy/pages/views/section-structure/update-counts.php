{% block form-body %}

<?php if ($updated) { ?>
	<p class="confirmation">Conteggi aggiornati</p>
<?php } ?>

<p class="">Cliccando qui sotto puoi aggiornare i conteggi delle vendite effettuate dell'ultimo anno. Questi valori saranno usati ordinare gli articoli in base alla popolarità.</p>

{% end form-body %}

{% block form-footer %}

<button type="submit" class="btn button--auto-width" name="update" value="recent">Aggiorna i conteggi</button>

{% end form-footer %}