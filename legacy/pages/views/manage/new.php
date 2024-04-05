{% block form-body %}

<p class="txt-jus pad-t-1">
	Hai creato il tuo negozio! Clicca qui sotto per caricare la tua prima grafica. Il tuo negozio non è ancora visibile al pubblico, ma verrà valutato e approvato entro 48h dal caricamento del tuo primo lavoro.
</p>

{% end form-body %}

{% block form-footer %}

<a href="<?= client_url('/manage/portfolio/add?id=' . auth()->id()) ?>" class="button">Carica una grafica</a>

{% end form-footer %}