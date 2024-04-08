{% block form-body %}

<?= generate_form($inputs, false, $error)	?>

<p class="mar-t-2">
	Il numero dell'ordine è indicato nella email di conferma ricevuta dal nostro sito e <strong>NON</strong> è il numero di ricevuta indicato nell'email ricevuta da PayPal. L'indirizzo email deve essere quello inizialmente utilizzato per effettuare l'ordine.
</p>

{% end form-body %}

{% block form-footer %}

<button type="submit" name="submit" id="submit" class="btn">Ricerca</button>

{% end form-footer %}