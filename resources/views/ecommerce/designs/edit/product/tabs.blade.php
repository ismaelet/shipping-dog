<div class="tabs">

	@foreach ($tabs as $key => $tab)
		<h3
			class="tab <?= $key == 0 ? 'active' : '' ?> uppercase"
			data-id="<?= $key + 1 ?>"
		><?= $tab[0] ?></h3>
	@endforeach

</div>
