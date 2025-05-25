@php

	use App\Legacy\System\DB;
	use App\Models\Ecommerce\Product;
	use App\Models\Ecommerce\Product\ProductGender;
	use App\Models\Ecommerce\Product\ProductType;
	use App\Models\Ecommerce\Seller;
	use App\Legacy\Ecommerce\Product\Type as LegacyProductType;

	$seed = 0;
	$productCount = 0;
	$size = 40;
	$search = '';
	$searchTypeTag = false;
	$searchGenderTag = false;
	$group = false;
	$slug = false;
	$sort = 'popular';
	$page = 1;
	$edit = false;
	$theme = 'dark';
	$id = request()->seller ? (int) request()->seller : (int) request()->id;
	$seller = Seller::find($id);

	if (isset($_GET['search'])) {
	    $search = $_GET['search'];
	}
	if (isset($_GET['type'])) {
	    $searchTypeTag = $_GET['type'];
	}
	if (isset($_GET['gender'])) {
	    $searchGenderTag = $_GET['gender'];
	}
	if (isset($_GET['group'])) {
	    $group = $_GET['group'];
	}
	if (isset($_GET['tag'])) {
	    $slug = $_GET['tag'];
	}
	if (isset($_GET['page'])) {
	    $page = (int) $_GET['page'];
	}
	if (isset($_GET['edit']) && is_admin()) {
	    $edit = true;
	}

	// Set sorting
	if (isset($_GET['sort']) && in_array($_GET['sort'], ['new', 'popular', 'custom'])) {
	    $sort = $_GET['sort'];
	} elseif ($seller) {
	    switch (DB::find('sort FROM stores WHERE user_id = ?', $seller->user_id)) {
	        case 3:
	            $sort = 'custom';
	            break;

	        case 2:
	            $sort = 'new';
	            break;

	        default:
	            $sort = 'popular';
	            break;
	    }
	}

	$start = $page ? $size * ($page - 1) : 1;

	// Start building the query
	$products = Product::join('stores', 'products.user_id', '=', 'stores.user_id')
	    ->leftJoin('designs', 'products.design_id', '=', 'designs.id')
	    ->where('products.status', 1)
	    ->where('products.deleted', 0);

	if ($seller) {
	    // Filter for a specific shop
	    $products->where('products.user_id', $seller->user_id);
	} else {
	    // Filter for the general shop
	    $products->where('stores.status', 1)->where('stores.hidden', 0);
	}

	// Order the products
	switch ($sort) {
	    case 'new':
	        $products->orderBy('products.time', 'desc')->orderBy('design_id', 'desc')->orderBy('type_id', 'asc');
	        break;

	    case 'popular':
	        $products
	            ->orderBy('products.recent_sales', 'desc')
	            ->orderBy('products.sales', 'desc')
	            ->orderBy('design_id', 'desc')
	            ->orderBy('type_id', 'asc');
	        break;

	    case 'custom':
	        $products->orderBy('type_id', 'asc')->orderBy('designs.sort', 'asc');
	        break;
	}

	// Filter by keyword
	if ($search) {
	    $products->where(function ($query) use ($search) {
	        $query
	            ->where('products.name', 'like', '%' . $search . '%')
	            ->orWhere('stores.name', 'like', '%' . $search . '%')
	            ->orWhereIn('design_id', function ($query) use ($search) {
	                $query
	                    ->select('design_id')
	                    ->from('design_tags')
	                    ->where('tags', 'like', '%' . $search . '%');
	            });
	    });
	}

	// Filters by type
	if ($searchTypeTag) {
	    $products->where('type_id', ProductType::where('tag', $searchTypeTag)->value('id'));
	}

	// Filters by gender
	if (in_array($searchGenderTag, ['m', 'f'])) {
	    $searchGender = ProductGender::where('tag', $searchGenderTag)->first();

	    $products->where(function ($query) use ($searchGender) {
	        $query->whereIn('type_id', ProductType::whereIn('gender_id', [$searchGender->id, 3])->get('id'));
	    });
	}

	// Filters by group
	if ($group) {
	    $products->whereIn('type_id', ProductType::where('category', $group)->get('id'));
	}

	// Filter by tag
	if ($slug) {
	    $products->whereIn('design_id', function ($query) {
	        $query
	            ->select('design_id')
	            ->from('design_tags')
	            ->where('tags', 'like', '%' . $slug . '%');
	    });
	}

	/**
	 * * Fetch the products
	 */
	// Get the product count
	$productCount = $products->count();

	// Get the products
	$products = $products
	    ->limit($size)
	    ->offset($start)
	    ->with(
	        'type',
	        'design',
	        'design_with_trashed',
	        'design.seller',
	        'design.logo_group',
	        'design.auth_user_like',
	        'seller',
	        'seller.prices',
	        'color',
	        'specific_price',
	        'base_price',
	        'position',
	    )
	    ->get('products.*');

	// store the result
	$productCount = $productCount;
	$products = $products;
@endphp

<form
	action="#store"
	method="GET"
	autocomplete="off"
	id="store"
	class="theme-<?= $theme ?>"
>

	<?php if ($seller) { ?>
	<input
		type="hidden"
		name="id"
		value="<?= $seller->id ?>"
	>
	<?php } ?>

	<input
		type="hidden"
		id="js-itemN"
		value="<?= $productCount ?>"
	>

	<!-- Sort items -->
	<section id="shop-sort">

		<label>
			<input
				type="checkbox"
				class="sort-cbx hidden"
				name="sort"
				value="new"
				<?php if ($sort == 'new') {
				    echo 'checked';
				} ?>
			>
			<p class="sort_txt clickable">Nuovi</p>
		</label>

		<p class="sort_txt">&middot;</p>

		<label>
			<input
				type="checkbox"
				class="sort-cbx hidden"
				name="sort"
				value="popular"
				<?php if ($sort == 'popular') {
				    echo 'checked';
				} ?>
			>
			<p class="sort_txt clickable">Popolari</p>
		</label>

		<button
			type="submit"
			id="sort"
			class="hidden"
		></button>

	</section>

	<!-- Search bar -->
	<section id="shop-search">

		<p
			id="filter-text"
			onclick="$(this).toggleClass('open'); $(this).next().toggleClass('open');"
		>
			Filtra la ricerca
			<span class="search-ddown-ico icon"><?= embed_icon('arrow-simple-down') ?></span>
		</p>

		<div id="search-sec">
			<div
				id="search-mob-box"
				class="pad-b-3"
			>
				<div class="mar-b-2">

					<div
						id="search-inp-box"
						class="al-mid grid"
					>

						<input
							type="text"
							id="search-inp"
							class=""
							name="search"
							value="<?= $search ?>"
							placeholder="Cerca un prodotto"
							maxlength="30"
							onfocus="$(this).parent().addClass('focus');"
							onblur="$(this).parent().removeClass('focus');"
							autocomplete="off"
						>

						<button
							type="submit"
							id="search-ico"
							class="clickable icon"
						><?= embed_icon('search') ?></button>

					</div>

				</div>

				<div class="srch-box">
					<h3 class="srch-title">Modello</h3>

					<!-- Men clothing -->
					<div>
						<input
							type="checkbox"
							id="gender-cbx-m"
							name="gender"
							class="gender-cbx hidden"
							value="m"
							<?php if ($searchGenderTag == 'm') {
							    echo 'checked';
							} ?>
						>

						<label
							class="gender-title-box"
							for="gender-cbx-m"
						>
							<p class="gender-title">
								<span class="gender-ico icon"><?= embed_icon('arrow-simple-down') ?></span>
								<span class="">Uomo</span>
							</p>
						</label>

						<div class="gender-ddown">
							<?php
									if ($seller) $res = DB::select("
											id FROM product_types WHERE category='c' AND (gender_id=1 OR gender_id=3) AND hidden = 0 AND ID IN (SELECT type_id FROM products WHERE user_id = ? AND status = 1) ORDER BY id ASC", [$seller->user_id]);

									else $res = DB::select("
											id FROM product_types WHERE category='c' AND (gender_id=1 OR gender_id=3) AND hidden = 0 AND ID IN (SELECT type_id FROM products WHERE status = 1) ORDER BY id ASC");

									while ($row = $res->fetch_assoc()) {

										$type = new LegacyProductType($row['id']) ?>

							<label>
								<input
									type="checkbox"
									class="type-cbx hidden"
									name="type"
									value="<?= $type->slug ?>"
									<?php if ($searchGenderTag == 'm' && $searchTypeTag == $type->slug) {
									    echo 'checked';
									} ?>
								>
								<p class="type-title"><?= $type->names ?></p>
							</label>
							<?php } ?>
						</div>
					</div>

					<!-- Women clothing -->
					<div>
						<input
							type="checkbox"
							id="gender-cbx-f"
							name="gender"
							class="gender-cbx hidden"
							value="f"
							<?php if ($searchGenderTag == 'f') {
							    echo 'checked';
							} ?>
						>

						<label
							class="gender-title-box"
							for="gender-cbx-f"
						>
							<p class="gender-title">
								<span class="gender-ico icon"><?= embed_icon('arrow-simple-down') ?></span>
								<span class="">Donna</span>
							</p>
						</label>

						<div class="gender-ddown">
							<?php

									if ($seller) {
										$res = DB::select("
											id FROM product_types WHERE category='c' AND (gender_id=2 OR gender_id=3) AND (hidden = 0 OR ID=115) AND ID IN (SELECT type_id FROM products WHERE user_id = ? AND status = 1) ORDER BY id ASC", [$seller->user_id]);
									} else {
										$res = DB::select("
											id FROM product_types WHERE category='c' AND (gender_id=2 OR gender_id=3) AND (hidden = 0 OR ID=115) AND ID IN (SELECT type_id FROM products WHERE status = 1) ORDER BY id ASC");
									}

									while ($row = $res->fetch_assoc()) {
										$type = new LegacyProductType($row['id']);
									?>

							<label>
								<input
									type="checkbox"
									class="type-cbx hidden"
									name="type"
									value="<?= $type->slug ?>"
									<?php if ($searchGenderTag == 'f' && $searchTypeTag == $type->slug) {
									    echo 'checked';
									} ?>
								>
								<p class="type-title"><?= $type->names ?></p>
							</label>
							<?php } ?>
						</div>
					</div>

					<!-- Accessories -->
					<div>
						<input
							type="checkbox"
							id="gender-cbx-u"
							name="gender"
							class="gender-cbx hidden"
							value="u"
							<?php if ($searchGenderTag == 'u') {
							    echo 'checked';
							} ?>
						>

						<label
							class="gender-title-box"
							for="gender-cbx-u"
						>
							<p class="gender-title">
								<span class="gender-ico icon"><?= embed_icon('arrow-simple-down') ?></span>
								<span class="">Accessori</span>
							</p>
						</label>

						<div class="gender-ddown">
							<?php

									if ($seller) {
										$res = DB::select("
											id FROM product_types WHERE (category='m' OR ID=111) AND ID IN (SELECT type_id FROM products WHERE user_id = ? AND status = 1) ORDER BY name ASC", [$seller->user_id]);
									} else {
										$res = DB::select("
											id FROM product_types WHERE (category='m' OR ID=111) AND ID IN (SELECT type_id FROM products WHERE status = 1) ORDER BY name ASC");
									}

									while ($row = $res->fetch_assoc()) {

										$type = new LegacyProductType($row['id']) ?>

							<label>
								<input
									type="checkbox"
									class="type-cbx hidden"
									name="type"
									value="<?= $type->slug ?>"
									<?php if ($searchTypeTag == $type->slug) {
									    echo 'checked';
									} ?>
								>
								<p class="type-title"><?= $type->names ?></p>
							</label>
							<?php } ?>
						</div>
					</div>

					<button
						type="submit"
						id="filter-type"
						class="hidden"
					></button>

					@if ($searchTypeTag || $searchGenderTag)
						<p
							id="remove-filter-type"
							class="remove-filter clickable pad-t-1"
						>
							<span class="font-semibold text-neutral-700">x</span>
							rimuovi filtro
						</p>
					@endif
				</div>

				<!-- Tags -->
				<!-- <?php if (!$seller) {
									$res_tags = DB::select('tag FROM sys_tags WHERE hide=0 ORDER BY count DESC LIMIT 20') ?>

							<div class="srch-box gallery">
								<h3 class="srch-title pad-t-3 mar-b-05">Tag popolari</h3>
								<?php while ($row_tags = $res_tags->fetch_assoc()) { ?>
									<label>
										<input type="checkbox" name="tag" value="<?= $row_tags['tag'] ?>" class="tag-cbx hidden" <?php if ($row_tags['tag'] == $slug) {
										    echo 'checked';
										} ?>>
										<p class="tag"><?= $row_tags['tag'] ?></p>
									</label>
								<?php } ?>

								<button type="submit" id="filter_tag" class="hidden"></button>

								<?php if ($slug) { ?>
									<p id="remove-filter-tag" class="remove-filter clickable pad-t-1">
										<span class="txt-blk font-semibold">x</span>
										rimuovi filtro
									</p>
								<?php } ?>
							</div>
						<?php } ?> -->

			</div>
		</div>
	</section>

	<!-- Items -->
	<section
		id="shop-items"
		class="pad-b-3"
	>

		<div
			id="items"
			class="items_panel"
			method="GET"
		>

			<?php if ($edit) { ?>
			<button
				type="submit"
				id="hide-items"
				name="hide-items"
				class="hidden"
			></button>
			<?php } ?>

			<div
				id="load"
				class="js-shop-load"
			>
			</div>

			<div class="grid5 grid4-1440w grid3-1024w grid2-480w col-gap1 row-gap2">

				@foreach ($products as $product)
					<x-shop.product-card :$product />
				@endforeach

			</div>

			<?php if (!$products) { ?>

			<p id="store-empty">
				<span class="store-empty-ico"></span><br><br>
				Nessun articolo trovato
			</p>

			<?php } ?>

			<!-- Pages -->
			<?php

					$pageN = ceil($productCount / $size);

					if ($pageN > 1) {

						$base = $page - 2;

						if ($base < 1) $base = 1;

						$top = $base + 4;

						if ($top > $pageN) {
							$top = $pageN;
							$i = $top - 4;
						} else $i = $base;

						if ($i < 1) $i = 1 ?>

			<div
				id="page-box"
				class="mar-t-3 text-center"
			>

				<input
					type="hidden"
					id="page-inp"
					name="page"
					value="<?= $page ?>"
				>

				<?php if ($base > 1) { ?><p class="page_txt disabled">...</p><?php } ?>

				<?php while ($i <= $top) { ?>
				<h1
					class="page_txt clickable <?php if ($i == $page) {
					    echo 'selected disabled';
					} ?>"
					data-page="<?= $i ?>"
				><?= $i ?></h1>
				<?php $i++;
							} ?>

				<?php if ($pageN > $top) { ?><p class="page_txt disabled">...</p><?php } ?>

				<button
					type="submit"
					id="page-btn"
					class="hidden"
				></button>

			</div>

			<?php } ?>
		</div>
	</section>

</form>
