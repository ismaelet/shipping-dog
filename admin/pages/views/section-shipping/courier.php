{% block form-body %}

<div class="form-field">
	<label class="label">Scarica dall'ordine, all'ordine:</label>
	<input type="number" name="first_cart_id" value="<?= $firstCart->ID ?? '' ?>" placeholder="Scarica dall'ordine numero" data-size="2" required>
	<input type="number" name="last_cart_id" value="<?= $lastCart->ID ?? '' ?>" placeholder="Fino all'ordine numero" data-size="2" required>
</div>

{% end form-body %}

{% block form-footer %}

<button type="submit" name="submit" class="button">Scarica le etichette</button>
<button type="submit" name="process-all" class="button button--secondary">Elabora gli ordini</button>

{% end form-footer %}