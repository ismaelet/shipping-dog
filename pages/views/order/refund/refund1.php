{% block form-body %}

<p class="mar-b-1">
	Per effettuare un cambio di taglia o un rimborso compila i campi qui sotto.<br><br>
	Il numero dell'ordine è indicato nella email di conferma ricevuta dal nostro sito e <strong>NON</strong> è il numero di ricevuta indicato nell'email ricevuta da PayPal. L'indirizzo email deve essere quello inizialmente utilizzato per effettuare l'ordine.
</p>

<?= generate_form($inputs, false, $error) ?>

{% end form-body %}

{% block form-footer %}

<button type="submit" name="submit" class="button">Ricerca</button>

{% end form-footer %}