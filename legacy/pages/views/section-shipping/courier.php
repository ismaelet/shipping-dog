{% block form-body %}

<div class="form-field">
	<label class="label">Scarica dall'ordine, all'ordine:</label>
	<input type="number" class="input" name="first_cart_id" value="<?= $firstCart->ID ?? '' ?>" placeholder="Scarica dall'ordine numero" data-size="2" required>
	<input type="number" class="input" name="last_cart_id" value="<?= $lastCart->ID ?? '' ?>" placeholder="Fino all'ordine numero" data-size="2" required>
</div>

{% end form-body %}

{% block form-footer %}

<button type="submit" name="submit" class="btn button--auto-width">Scarica le etichette</button>
<button type="submit" name="process-all" class="btn btn--secondary button--auto-width">Elabora gli ordini</button>

{% end form-footer %}