<?php

namespace Legacy\Classes\Ecommerce;

use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductGender;
use App\Models\Ecommerce\Product\ProductType;
use Illuminate\Support\Facades\Cache;
use Legacy\Classes\Ecommerce\Product\Type as LegacyProductType;

class Shop {

	public $id = 0;
	public $seed = 0;
	public $productCount = 0;
	public $size = 40;
	public $search = '';
	public $type = false;
	public $gender = false;
	public $group = false;
	public $slug = false;
	public $sort = 'popular';
	public $page = 1;
	public $edit = false;
	public $theme = false;

	private $products;

	function __construct($theme = 'dark') {

		$this->theme = $theme;

		if (isset($_GET['id'])) $this->id = (int) $_GET['id'];
		if (isset($_GET['search'])) $this->search = $_GET['search'];
		if (isset($_GET['type'])) $this->type = $_GET['type'];
		if (isset($_GET['gender'])) $this->gender = $_GET['gender'];
		if (isset($_GET['group'])) $this->group = $_GET['group'];
		if (isset($_GET['tag'])) $this->slug = $_GET['tag'];
		if (isset($_GET['page'])) $this->page = (int) $_GET['page'];
		if (isset($_GET['edit']) && is_admin()) $this->edit = true;

		// Set sorting
		if (isset($_GET['sort']) && in_array($_GET['sort'], ['new', 'popular', 'custom'])) {
			$this->sort = $_GET['sort'];
		} else if ($this->id) {
			switch (\Legacy\System\Classes\LegacyDB::find('sort FROM stores WHERE user_id = ?', $this->id)) {
				case 3:
					$this->sort = 'custom';
					break;

				case 2:
					$this->sort = 'new';
					break;

				default:
					$this->sort = 'popular';
					break;
			}
		}

		$start = $this->page ? $this->size * ($this->page - 1) : 1;

		// Start building the query
		$products = Product::join('stores', 'products.user_id', '=', 'stores.user_id')
			->leftJoin('designs', 'products.design_id', '=', 'designs.id')
			->where('products.status', 1)
			->where('products.deleted', 0);

		if ($this->id) {
			// Filter for a specific shop
			$products
				->where('products.user_id', $this->id);
		} else {
			// Filter for the general shop
			$products
				->where('stores.status', 1)
				->where('stores.hidden', 0);
		}

		// Order the products
		switch ($this->sort) {
			case 'new':
				$products->orderBy('products.time', 'desc')
					->orderBy('design_id', 'desc')
					->orderBy('type_id', 'asc');
				break;

			case 'popular':
				$products->orderBy('products.recent_sales', 'desc')
					->orderBy('products.sales', 'desc')
					->orderBy('design_id', 'desc')
					->orderBy('type_id', 'asc');
				break;

			case 'custom':
				$products->orderBy('products.sort', 'asc')
					->orderBy('designs.sort', 'asc')
					->orderBy('type_id', 'asc');
				break;
		}

		// Filter by keyword
		if ($this->search) {
			$products->where(function ($query) {
				$query->where('products.name', 'like', '%' . $this->search . '%')
					->orWhere('stores.name', 'like', '%' . $this->search . '%')
					->orWhereIn('design_id', function ($query) {
						$query->select('design_id')
							->from('design_tags')
							->where('tags', 'like', '%' . $this->search . '%');
					});
			});
		}

		// Filters by type
		if ($this->type) {
			$products->where('type_id', ProductType::where('tag', $this->type)->value('id'));
		}

		// Filters by gender
		if (in_array($this->gender, ['m', 'f'])) {
			$gender = ProductGender::where('tag', $this->gender)->first();

			$products->where(function ($query) use ($gender) {
				$query->whereIn('type_id', ProductType::whereIn('gender_id', [$gender->id, 3])->get('id'));
			});
		}

		// Filters by group
		if ($this->group) {
			$products->whereIn('type_id', ProductType::where('category', $this->group)->get('id'));
		}

		// Filter by tag
		if ($this->slug) {
			$products->whereIn('design_id', function ($query) {
				$query->select('design_id')
					->from('design_tags')
					->where('tags', 'like', '%' . $this->slug . '%');
			});
		}

		/**
		 * * Fetch the products
		 */
		// Setup the cache
		$cacheKeyParameters = md5(http_build_query(request()->all()));
		$cacheInterval = $this->id ? 10 : 60 * 60;

		// Get the product count
		$productCount = Cache::remember('shop-count-' . $cacheKeyParameters, $this->id ? $cacheInterval : $cacheInterval * 24, function () use ($products) {
			return $products->count();
		});

		// Get the products
		$products = $products->limit($this->size)
			->offset($start)
			->with('type', 'design', 'design.seller', 'design.logo_group', 'design.auth_user_like', 'seller', 'color', 'specific_price', 'seller_price', 'base_price', 'position')
			->get('products.*');

		// store the result
		$this->productCount = $productCount;
		$this->products = $products;
	}

	public function render() { ?>

		<form action="#store" method="GET" autocomplete="off" id="store" class="theme-<?= $this->theme ?>">

			<?php if ($this->id) { ?>
				<input type="hidden" name="id" value="<?= $this->id ?>">
			<?php } ?>

			<input type="hidden" id="js-itemN" value="<?= $this->productCount ?>">

			<!-- Sort items -->
			<section id="shop-sort">

				<label>
					<input type="checkbox" class="sort-cbx hidden" name="sort" value="new" <?php if ($this->sort == 'new') echo 'checked' ?>>
					<p class="sort_txt clickable">Nuovi</p>
				</label>

				<p class="sort_txt">&middot;</p>

				<label>
					<input type="checkbox" class="sort-cbx hidden" name="sort" value="popular" <?php if ($this->sort == 'popular') echo 'checked' ?>>
					<p class="sort_txt clickable">Popolari</p>
				</label>

				<button type="submit" id="sort" class="hidden"></button>

			</section>

			<!-- Search bar -->
			<section id="shop-search">

				<p id="filter-text" onclick="$(this).toggleClass('open'); $(this).next().toggleClass('open');">
					Filtra la ricerca
					<span class="search-ddown-ico icon "><?= embed_icon('navigation/arrow-simple-down') ?></span>
				</p>

				<div id="search-sec">
					<div id="search-mob-box" class="pad-b-3">
						<div class="mar-b-2">

							<div id="search-inp-box" class="grid al-mid">

								<input type="text" id="search-inp" class="" name="search" value="<?= $this->search ?>" placeholder="Cerca un prodotto" maxlength="30" onfocus="$(this).parent().addClass('focus');" onblur="$(this).parent().removeClass('focus');" autocomplete="off">

								<button type="submit" id="search-ico" class="clickable icon"><?= embed_icon('actions/search') ?></button>

							</div>

						</div>

						<div class="srch-box">
							<h3 class="srch-title">Modello</h3>

							<!-- Men clothing -->
							<div>
								<input type="checkbox" id="gender-cbx-m" name="gender" class="gender-cbx hidden" value="m" <?php if ($this->gender == 'm') echo 'checked' ?>>

								<label class="gender-title-box" for="gender-cbx-m">
									<p class="gender-title">
										<span class="gender-ico icon"><?= embed_icon('navigation/arrow-simple-down') ?></span>
										<span class="">Uomo</span>
									</p>
								</label>

								<div class="gender-ddown">
									<?php
									if ($this->id) $res = \Legacy\System\Classes\LegacyDB::select("
											id FROM product_types WHERE category='c' AND (gender_id=1 OR gender_id=3) AND hidden = 0 AND ID IN (SELECT type_id FROM products WHERE user_id = ? AND status = 1) ORDER BY id ASC", [$this->id]);

									else $res = \Legacy\System\Classes\LegacyDB::select("
											id FROM product_types WHERE category='c' AND (gender_id=1 OR gender_id=3) AND hidden = 0 AND ID IN (SELECT type_id FROM products WHERE status = 1) ORDER BY id ASC");

									while ($row = $res->fetch_assoc()) {

										$type = new LegacyProductType($row['id']) ?>

										<label>
											<input type="checkbox" class="type-cbx hidden" name="type" value="<?= $type->slug ?>" <?php if ($this->gender == 'm' && $this->type == $type->slug) echo 'checked' ?>>
											<p class="type-title"><?= $type->names ?></p>
										</label>
									<?php } ?>
								</div>
							</div>

							<!-- Women clothing -->
							<div>
								<input type="checkbox" id="gender-cbx-f" name="gender" class="gender-cbx hidden" value="f" <?php if ($this->gender == 'f') echo 'checked' ?>>

								<label class="gender-title-box" for="gender-cbx-f">
									<p class="gender-title">
										<span class="gender-ico icon "><?= embed_icon('navigation/arrow-simple-down') ?></span>
										<span class="">Donna</span>
									</p>
								</label>

								<div class="gender-ddown">
									<?php

									if ($this->id) {
										$res = \Legacy\System\Classes\LegacyDB::select("
											id FROM product_types WHERE category='c' AND (gender_id=2 OR gender_id=3) AND (hidden = 0 OR ID=115) AND ID IN (SELECT type_id FROM products WHERE user_id = ? AND status = 1) ORDER BY id ASC", [$this->id]);
									} else {
										$res = \Legacy\System\Classes\LegacyDB::select("
											id FROM product_types WHERE category='c' AND (gender_id=2 OR gender_id=3) AND (hidden = 0 OR ID=115) AND ID IN (SELECT type_id FROM products WHERE status = 1) ORDER BY id ASC");
									}

									while ($row = $res->fetch_assoc()) {
										$type = new LegacyProductType($row['id']);
									?>

										<label>
											<input type="checkbox" class="type-cbx hidden" name="type" value="<?= $type->slug ?>" <?php if ($this->gender == 'f' && $this->type == $type->slug) echo 'checked' ?>>
											<p class="type-title"><?= $type->names ?></p>
										</label>
									<?php } ?>
								</div>
							</div>

							<!-- Accessories -->
							<div>
								<input type="checkbox" id="gender-cbx-u" name="gender" class="gender-cbx hidden" value="u" <?php if ($this->gender == 'u') echo 'checked' ?>>

								<label class="gender-title-box" for="gender-cbx-u">
									<p class="gender-title">
										<span class="gender-ico icon "><?= embed_icon('navigation/arrow-simple-down') ?></span>
										<span class="">Accessori</span>
									</p>
								</label>

								<div class="gender-ddown">
									<?php

									if ($this->id) {
										$res = \Legacy\System\Classes\LegacyDB::select("
											id FROM product_types WHERE (category='m' OR ID=111) AND ID IN (SELECT type_id FROM products WHERE user_id = ? AND status = 1) ORDER BY name ASC", [$this->id]);
									} else {
										$res = \Legacy\System\Classes\LegacyDB::select("
											id FROM product_types WHERE (category='m' OR ID=111) AND ID IN (SELECT type_id FROM products WHERE status = 1) ORDER BY name ASC");
									}

									while ($row = $res->fetch_assoc()) {

										$type = new LegacyProductType($row['id']) ?>

										<label>
											<input type="checkbox" class="type-cbx hidden" name="type" value="<?= $type->slug ?>" <?php if ($this->type == $type->slug) echo 'checked' ?>>
											<p class="type-title"><?= $type->names ?></p>
										</label>
									<?php } ?>
								</div>
							</div>

							<button type="submit" id="filter-type" class="hidden"></button>

							<?php if ($this->type || $this->gender) { ?>
								<p id="remove-filter-type" class="remove-filter clickable pad-t-1">
									<span class="font-semibold txt-blk">x</span>
									rimuovi filtro
								</p>
							<?php } ?>
						</div>

						<!-- Tags -->
						<?php if (!$this->id) {
							$res_tags = \Legacy\System\Classes\LegacyDB::select('tag FROM sys_tags WHERE hide=0 ORDER BY count DESC LIMIT 20') ?>

							<div class="srch-box gallery">
								<h3 class="srch-title pad-t-3 mar-b-05">Tag popolari</h3>
								<?php while ($row_tags = $res_tags->fetch_assoc()) { ?>
									<label>
										<input type="checkbox" name="tag" value="<?= $row_tags['tag'] ?>" class="tag-cbx hidden" <?php if ($row_tags['tag'] == $this->slug) echo 'checked' ?>>
										<p class="tag"><?= $row_tags['tag'] ?></p>
									</label>
								<?php } ?>

								<button type="submit" id="filter_tag" class="hidden"></button>

								<?php if ($this->slug) { ?>
									<p id="remove-filter-tag" class="remove-filter clickable pad-t-1">
										<span class="font-semibold txt-blk">x</span>
										rimuovi filtro
									</p>
								<?php } ?>
							</div>
						<?php } ?>

					</div>
				</div>
			</section>

			<!-- Items -->
			<section id="shop-items" class="pad-b-3">

				<div id="items" class="items_panel" method="GET">

					<?php if ($this->edit) { ?>
						<button type="submit" id="hide-items" name="hide-items" class="hidden"></button>
					<?php } ?>

					<div id="load" class="js-shop-load">
					</div>

					<div class="grid5 grid4-1440w grid3-1024w grid2-480w col-gap1 row-gap2">

						<?php foreach ($this->products as $product) {
							echo ViewManager::renderProductCard($product);
						} ?>

					</div>

					<?php if (!$this->products) { ?>

						<p id="store-empty">
							<span class="store-empty-ico"></span><br><br>
							Nessun articolo trovato
						</p>

					<?php } ?>

					<!-- Pages -->
					<?php

					$pageN = ceil($this->productCount / $this->size);

					if ($pageN > 1) {

						$base = $this->page - 2;

						if ($base < 1) $base = 1;

						$top = $base + 4;

						if ($top > $pageN) {
							$top = $pageN;
							$i = $top - 4;
						} else $i = $base;

						if ($i < 1) $i = 1 ?>

						<div id="page-box" class="text-center mar-t-3">

							<input type="hidden" id="page-inp" name="page" value="<?= $this->page ?>">

							<?php if ($base > 1) { ?><p class="page_txt disabled">...</p><?php } ?>

							<?php while ($i <= $top) { ?>
								<h1 class="page_txt clickable <?php if ($i == $this->page) echo 'selected disabled' ?>" data-page="<?= $i ?>"><?= $i ?></h1>
							<?php $i++;
							} ?>

							<?php if ($pageN > $top) { ?><p class="page_txt disabled">...</p><?php } ?>

							<button type="submit" id="page-btn" class="hidden"></button>

						</div>

					<?php } ?>
				</div>
			</section>

		</form>

<?php }
}
