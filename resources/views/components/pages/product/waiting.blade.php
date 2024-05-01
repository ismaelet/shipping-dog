<section
	id="right-panel"
	class="grid"
>
	<div class="">
		<!-- Title -->
		<h1 id="title">{{ $product->name }}</h1>

		<p id="author">{{ $product->type->name }}
			by <a
				class="txt-c1-h"
				href="{{ $product->seller->url() }}"
			>
				{{ $product->seller->name }}
			</a>
		</p>

		<!-- Price -->
		<div class="price-block">

			@if ($product->userIsOwner())

				<!-- Price when there's a discount -->
				<p class="price price-old heading-like">{{ $product->fullPriceString() }}</p>
				<p class="price price-promo heading-like">{{ $product->sellerPriceString() }}</p>

				<div class="flex-group">
					<p class="price-txt">Prezzo scontato{{ $product->userIsOwner() ? ' solo per te' : '' }}</p>

					@if ($product->userIsOwner())
						{{ info_box("Questo prezzo speciale è visibile a te, perché sei il proprietario di questo articolo. I clienti vedranno solo il prezzo normale di <span class=''>" . $product->fullPriceString() . '</span>') }}
					@endif
				</div>
			@else
				<!-- Normal price -->
				<p class="price txt-bold price-def heading-like js-price">{{ $product->customerPriceString() }}</p>

			@endif

		</div>

		<!-- Colors -->
		@if (!empty($legacyProduct->type->availableColorSlugs))

			@php
				$res = \Legacy\System\Classes\LegacyDB::select('id FROM items_colors ORDER BY reorder ASC, name ASC');
			@endphp

			<section
				id="color"
				class="field {{ count($legacyProduct->availableColorSlugs) < 2 ? 'hide' : '' }}"
			>
				<p class="lbl">Colore</p>
				<div class="grid">

					@while ($row = $res->fetch_assoc())
						@php
							$color = new Color($row['id']);

							if (
							    (in_array($color->slug, $legacyProduct->type->availableColorSlugs) || $legacyProduct->isCustom) &&
							    in_array($color->slug, $legacyProduct->availableColorSlugs)
							);
						@endphp

						<input
							type="checkbox"
							class="cbx"
							id="cbx-color-<?= $color->slug ?>"
							name="cbx-color[]"
							value="<?= $color->id ?>"
							data-color="<?= $color->slug ?>"
							data-id="<?= $color->id ?>"
							<?php if ($color->slug == $legacyProduct->color->slug) {
							    echo 'checked';
							} ?>
						>

						<label
							for="cbx-color-<?= $color->slug ?>"
							class="color-box"
						>

							<div
								class="color-lbl <?= $color->bicolor ? 'bicolor' : false ?>"
								style="background-color: #<?= $color->hex ?>"
							>
								@if ($color->bicolor)
									<div
										class="bicolor-fill"
										style="background-color: #<?= $color->hex ?>"
									></div>
									<div
										class="bicolor-fill"
										style="background-color: #<?= $color->secondHex ?>"
									></div>
								@endif
							</div>

						</label>

					@endwhile

				</div>
			</section>

		@endif
		@endif

		<!-- Size -->
		@if (!empty($legacyProduct->type->sizes))
			@php
				$new = true;
				$size_table =
				    \Legacy\System\Classes\LegacyDB::exists('items_types_measures WHERE type_id=?', [$legacyProduct->type->id]) &&
				    $product->type->category == 'c';
				$res = \Legacy\System\Classes\LegacyDB::select('id FROM items_sizes');
			@endphp

			<section
				id="size"
				class="field"
			>
				<p class="lbl">{{ $product->type->category == 'c' ? 'Taglia' : 'Dimensione' }}

					@if (!$legacyProduct->isCustom && $size_table)
						<span
							class="lbox-open clickable info txt-c1-h"
							data-tar="#size-lbox"
						>info taglie</span>
					@endif

				</p>

				<div class="grid">

					@while ($row = $res->fetch_assoc())
						@php
							$sizeM = new Size($row['id']);
						@endphp

						@if (in_array($sizeM->slug, $legacyProduct->type->sizes))
							<div class="size_box">
								<input
									type="hidden"
									value="{{ $sizeM->price }}"
								>
								<input
									type="checkbox"
									class="cbx"
									id="cbx-type_{{ $sizeM->slug }}"
									name="cbx-size[]"
									value="{{ $sizeM->id }}"
									@if ($new) checked
										{{ $new = false }} @endif
								>

								<label
									class="size_lbl"
									for="cbx-type_{{ $sizeM->slug }}"
								>
									<h1 class="size_txt txt-upp">{{ $sizeM->name }}</h1>
								</label>
							</div>
						@endif
					@endwhile
				</div>

				<!-- Size table -->
				@if ($size_table)

					<div
						id="size-lbox"
						class="lbox"
					>

						<div class="lbox-bgr"></div>

						<div class="lbox-form-box">

							<div class="lbox-form">

								<div class="close-icon"></div>

								<p class="txt-15rem txt-bold mar-b-1 brb2 br-blk">Taglie:</p>
								<p class="">Se non vedi tutte le taglie scorri verso il basso.</p>

								<img
									src="{{ public_asset('sizes/img.jpg') }}"
									alt=""
									loading="lazy"
									class="size-table-img mar-t-1"
								>

								<div class="size-table-grid mar-b-1">

									<div class="_empty"></div>

									<p class="txt-bold txt-line">Larghezza:</p>

									<p class="txt-bold txt-line">Altezza:</p>

									@foreach ($legacyProduct->type->sizes as $size)
										@php

											$measures = \Legacy\System\Classes\LegacyDB::find(
											    'val FROM items_types_measures WHERE type_id=? AND
														size=?',
											    [$legacyProduct->type->id, $size],
											);

										@endphp

										<p class="size-table-tag txt-bold"><?= strtoupper($size) ?>:</p>

										<p class=""><?= explode(',', $measures)[0] ?> cm</p>

										<p class=""><?= explode(',', $measures)[1] ?> cm</p>
									@endforeach

								</div>

								@if (
									\Legacy\System\Classes\LegacyDB::exists('items_types_measures WHERE type_id=? AND size=?', [
										$legacyProduct->type->id,
										'mat',
									])) { ?>
									<p class="">Composizione:
										<?= \Legacy\System\Classes\LegacyDB::find('val FROM items_types_measures WHERE type_id=? AND size=?', [$legacyProduct->type->id, 'mat']) ?>
									</p>
								@endif

							</div>
						</div>
					</div>

				@endif

			</section>

		@endif

		<!-- Gender -->
		@if ($legacyProduct->type->genderId)
			@php
				$new = true;

				$genders = [];
				$genders[] = $legacyProduct->type->genderId;
				if ($legacyProduct->type->sibling) {
				    $genders[] = (new Type($legacyProduct->type->sibling))->genderId;
				}

			@endphp

			<section
				id="gender"
				class="field"
			>

				<div class="">
					<p class="lbl">Modello</p>

					@if ($legacyProduct->type->sibling && $legacyProduct->type->unisex)
						@php
							info_box(
							    "Qui puoi scegliere se vedere l'anteprima del prodotto su un modello maschile o femminile, ma ha scopo puramente illustrativo, in quanto tutte le taglie per questo articolo sono unisex.",
							);
						@endphp
					@endif
				</div>

				<div class="grid">

					@foreach ($genders as $key => $genderId)
						@php
							$gender = new Gender($genderId);

							if ($key) {
							    $relatedProduct = new \Legacy\Classes\Ecommerce\Product(
							        \Legacy\System\Classes\LegacyDB::find('id FROM items WHERE design_id = ? AND type_id = ?', [
							            $legacyProduct->design->id,
							            $legacyProduct->type->sibling,
							        ]),
							    );
						} @endphp

						<div class="gend_box">
							<input
								type="checkbox"
								class="cbx"
								id="cbx-gend_{{ $gender->slug }}"
								name="cbx-gender[]"
								value="{{ $gender->id }}"
								@if (!$key) checked @endif
							>

							<a
								@if ($key) {{ 'href="' . $relatedProduct->clientUrl . '"' }} @endif
								class="gend_lbl"
							>
								<h1 class="gend_txt">{{ $gender->name }}</h1>
							</a>
						</div>
					@endforeach

				</div>

			</section>

		@endif

		<!-- Position -->
		<div
			id="position"
			class="field {{ count($legacyProduct->availablePositionIds) < 2 || $legacyProduct->isCustom ? 'hidden' : '' }}"
		>

			<p class="lbl">Posizione della stampa</p>

			<div class="data-area">

				@foreach ($legacyProduct->availablePositionIds as $key => $position_id)
					@php
					$position = new Position($position_id); @endphp

					<label class="data-selector data-selector--position">
						<input
							type="checkbox"
							class="js-change-position"
							name="cbx-pos[]"
							value="{{ $position->id }}"
							@if (!$key) checked @endif
						>
						<p class="data-selector__label">{{ $position->name }}</p>
					</label>
				@endforeach

			</div>
		</div>

		<!-- Logos -->
		@if ($legacyProduct->hasLogos)

			<div class="logo-field field">
				<p class="lbl">{{ $legacyProduct->logoGroup->name }}</p>

				<div
					class="grid-flow"
					data-spacing="400"
				>
					<div class="logo-field__list">

						<!-- Checkbox for no logo -->
						@if (!$legacyProduct->logoGroup->forced)

							<label class="logo-field__item">
								<input
									type="radio"
									class="js-logo"
									name="logo-id"
									value="0"
									checked
								>
								{!! embed_icon('close') !!}
							</label>

							<!--checkboxs for extras-->
							@foreach ($product->logos() as $logoKey => $logo)
								<label
									class="logo-field__item"
									title="{{ $logo->name }}"
								>
									<input
										type="radio"
										class="js-logo"
										name="logo-id"
										value="{{ $logo->id }}"
										{{ $legacyProduct->logoGroup->forced && !$logoKey ? 'checked' : null }}
									>
									<img
										class="img-fill"
										src="{{ $logo->image() }}"
										alt=""
									>
								</label>
							@endforeach

					</div>

					<div
						class="logo-field__preview js-logo-image-background"
						class="{{ $legacyProduct->logoGroup->forced ? 'active' : null }}"
					>
						<div class="logo-field__preview-wrapper grid-flow stack-content">
							<img
								class="logo-field__preview-image js-front-image"
								src="{{ $legacyProduct->clientFullImage }}"
							>
							<img
								class="logo-field__preview-logo js-logo-image"
								src=""
								alt=""
							>
						</div>
					</div>
				</div>
			</div>

		@endif

		<!-- Quantity -->
		<div
			id="qty_cont"
			class="field hidden"
		>
			<p class="lbl">Quantità</p>

			<input
				type="text"
				id="quantity"
				name="quantity"
				value="1"
			>
			<div id="qty_box">
				<h1
					id="qty_pls"
					class="qty_edit on"
				>+</h1>
				<h1
					id="qty_min"
					class="qty_edit off"
				>-</h1>
			</div>
		</div>

		<!-- Add to cart -->
		<div class="mar-t-4">

			@if (($legacyProduct->statusId && !$legacyProduct->deleted) || $owner)

				<button
					type="submit"
					id="submit"
					name="submit"
					class="add-to-cart btn button--full-width"
				>
					{!! embed_icon('cart') !!}
					Aggiungi al carrello
				</button>

			@endif

			@if (!$legacyProduct->isCustom && $product->blankProduct())

				<p class="mar-t-1">Vuoi acquistare il prodotto senza grafica? <a
						class="txt-bold txt-c-title"
						href="{{ $product->blankProduct()->url() }}"
					>Clicca qui</a>.</p>

			@endif

		</div>
	</div>
</section>
</form>
</div>
