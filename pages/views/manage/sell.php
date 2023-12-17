{% block form-body %}

<p class="txt-jus pad-t-1">Iniziare a vendere le tue grafiche su <strong>Shipping Dog</strong> è facile, veloce, completamente gratuito e non è necessario avere partita IVA o nessun altro requisito.<br><br>Compila i campi qui sotto e crea, senza impegno, il tuo negozio. All'inizio il negozio sarà visibile solo a te, ma quando caricherai le prime grafiche lo valuteremo e pubblicheremo.</p>

<?= generate_form($inputs) ?>

{% end form-body %}

{% block form-footer %}

<button type="submit" id="submit" name="submit" class="button">Invia richiesta</button>

{% end form-footer %}