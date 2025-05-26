@props(['id', 'vip', 'collBlock'])

@php
	use App\Legacy\System\DB;
	use App\Legacy\Ecommerce\Design\Collection;

	//get collections
	if ($vip) {
	    $res = DB::select('id FROM collections WHERE user_id = ? ORDER BY position ASC, id DESC', [$id]);
	} else {
	    $res = DB::select(
	        'id FROM collections WHERE user_id = ? AND ID IN (SELECT collection FROM designs) ORDER BY position ASC, id DESC',
	        [$id],
	    );
	}

	//generate collections
	$i = $vip ? 1 : 0;

@endphp

@while ($row = $res->fetch_assoc())
	@php
		$i++;
		$coll = new Collection($row['id']);
	@endphp

	<div
		id="coll-box<?= $i ?>"
		class="coll-ele al-top coll-box sort-box <?php if ($i > $collBlock) {
		    echo 'hidden';
		} ?>"
		data-id="<?= $coll->id ?>"
	>

		<h3 class="coll-name txt-low text-center"><?= $coll->name ?></h3>

		<a
			class="collection relative"
			href="<?= $coll->url ?>"
			style="background-color: <?= $coll->color ?>;"
		>

			<div
				class="coll-img bgr <?php if (!$coll->img_cust) {
				    echo 'cont';
				} ?>"
				<?= $coll->img_m ? temp_bg($coll->img_m) : '' ?>
			></div>

			<div class="coll-cover bgr"></div>
		</a>

		{{-- <div class="mt-4 flex justify-center">
			<x-button
				variant="secondary"
				size="small"
			>Esplora</x-button>
		</div> --}}
	</div>
@endwhile
