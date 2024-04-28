@php

	use Legacy\View\Components\Interactions\LikeButton;
	use Legacy\Classes\Ecommerce\Product\Color;
	use Legacy\Classes\Ecommerce\Product\Gender;
	use Legacy\Classes\Ecommerce\Product\Position;
	use Legacy\Classes\Ecommerce\Product\Size;
	use Legacy\Classes\Ecommerce\Product\Type;

@endphp

@pushOnce('styles')
	<style>
		/* Containers */
		#item {
			margin-bottom: 4rem;
		}

		#item .content {
			grid-template-columns: 55fr 45fr;
		}

		#left-panel {
			width: 100%;
		}

		/* Warnings */
		#news {
			margin-top: 0;
		}

		/* Type */
		.item-type-title {
			border-bottom: 2px solid transparent;
			color: var(--text-color-fade);
			font-size: 1rem;
			line-height: 1.5rem;
			transform-origin: left;
			vertical-align: baseline;
			transition: border 0.2s, color 0.3s, font-size 0.2s;
		}

		.item-type-title:hover,
		.item-type-title.active {
			color: var(--text-color-strong);
		}

		.item-type-title.active {
			border-bottom: 2px solid;
			font-size: 1.2rem;
		}

		.type-slide {
			display: none;
		}

		.type-slide.active {
			display: block;
		}

		.type-slide .carousel {
			--columns: 3;
			--rows: 1;
			--step: 3;
			--gap: 1rem;
		}

		.type-slide .carousel-box {
			padding-bottom: 2rem;
		}

		.type-slide img {
			background-color: var(--surface-secondary);
			border-radius: 0.5rem;
			box-shadow: 0 1.2rem 1rem -1.2rem rgba(0, 0, 0, 0.4);
			aspect-ratio: 1;
		}

		[data-color-scheme='dark'] .type-slide img {
			box-shadow: 0 1.2rem 1rem -1.2rem rgba(0, 0, 0, 1);
		}

		/* Image */
		.item-base {
			background-color: transparent;
		}

		#item-img-box {
			padding-bottom: 100%;
			position: relative;
			z-index: 1;
		}

		#item-img {
			position: absolute;
			top: 0;
			bottom: 0;
			left: 0;
			right: 0;
			background-color: #e1e1e1;
			border-radius: var(--border-radius);
		}

		#img-pos-box {
			width: 7rem;
			left: -3.5rem;
			z-index: 1;
		}

		.img-pos {
			background-color: #ddd;
			border-radius: var(--border-radius);
			box-shadow: 0 1.6rem 1rem -1.2rem rgba(0, 0, 0, 0.3), -1px -1px 1px rgba(255, 255, 255, 0.6) inset;
			padding-bottom: 100%;
			margin: 1rem 0;
		}

		#img-load {
			display: none;
			background-color: rgba(255, 255, 255, 0.8);
		}

		#load-ico {
			width: 5rem;
			height: 5rem;
			top: 50%;
			transform: translateY(-50%);
			margin: 0 auto;
		}

		#img-zoom-icon {
			display: none;
			opacity: 0.8;
			width: 1.5rem;
			height: 1.5rem;
			position: absolute;
			top: 1rem;
			left: 1rem;
		}

		#img-zoom-bgr {
			display: none;
			background: rgba(154, 154, 154, 0.7);
			backdrop-filter: saturate(0);
			width: 100%;
			height: 100%;
			position: fixed;
			top: 0;
			left: 0;
			text-align: center;
			z-index: 101;
		}

		#img-zoom-box {
			height: calc(100% - 6rem);
			width: calc(100% - 6rem);
			top: 3rem;
			left: 3rem;
			overflow: auto;
			font-size: 0;
		}

		#img-zoom {
			max-width: 90%;
		}

		/* Item data */
		.field {
			margin-top: 2rem;
		}

		.field input {
			display: none;
		}

		.field .lbl {
			border-bottom: 1px solid var(--surface-secondary);
			padding-bottom: 5px;
			margin: 0 0 10px 0;
			font-size: 0.8rem;
			letter-spacing: 1px;
			font-family: var(--font-main);
			text-transform: uppercase;
		}

		.cbx:checked+label h1,
		.cbx:checked+a h1,
		.field label:has(:checked) {
			color: var(--fc-700);
		}

		.cbx:disabled+label h1,
		.cbx:disabled+a h1 {
			color: #888;
		}

		/* Title */
		#title {
			padding-bottom: 5px;
			font-weight: bold;
			width: 100%;
			word-wrap: break-word;
			font-size: 2.5rem;
		}

		#author {
			font-size: 11px;
			letter-spacing: 0.1em;
			text-transform: uppercase;
		}

		#author a {
			transition: color 0.15s;
		}

		/* Price */
		.price-block {
			padding-block: 20px 8px;
		}

		.price-def {
			padding-top: 1rem;
			letter-spacing: 0.05em;
			font-size: 32px;
			cursor: default;
		}

		.price-old {
			line-height: 1.7;
			cursor: default;
			color: var(--text-color-fade);
			font-size: 20px;
			text-decoration: line-through;
		}

		.price-promo,
		.price-owner {
			color: #bb0e0e;
			font-size: 25px;
			line-height: 1.7;
			cursor: default;
		}

		.price-txt {
			color: #bb0e0e;
			font-size: 12px;
		}

		/* Color */
		#color .grid {
			grid-template-columns: repeat(auto-fill, 2.2rem);
			grid-gap: 0.5rem;
		}

		.color-box {
			border: 2px solid var(--border-color-fade);
			border-radius: 50%;
			padding: 0.25rem;
			cursor: pointer;
			transition: border 0.2s;
		}

		.color-lbl {
			padding-bottom: 100%;
			border-radius: inherit;
		}

		.bicolor {
			display: grid;
			grid-template-rows: repeat(2, 1fr);
			overflow: hidden;
			transform: rotate(-45deg);
		}

		.cbx:checked+.color-box {
			border-color: var(--clr-1);
		}

		/* Size and gender */
		#size .lbl {
			overflow: auto;
		}

		#size .info {
			opacity: 0.8;
			float: right;
			text-transform: none;
		}

		#gender .tip-box {
			position: absolute;
			top: 0;
			right: 0;
		}

		#size .grid {
			display: flex;
			flex-wrap: wrap;
			gap: 5px;
			width: 100%;
		}

		#gender .grid {
			grid-template-columns: repeat(auto-fill, minmax(8rem, 1fr));
			grid-gap: 0.5rem;
		}

		.data-selector__label {
			padding: 0.5em 2em;
			border: 2px solid var(--border-clr-400);
			border-radius: var(--border-radius-1000);
			color: var(--fc-700);
			font-family: var(--ff-titles);
			letter-spacing: 0.03em;
			cursor: pointer;
			transition: border-color 0.2s, color 0.2s;
		}

		input:checked+.data-selector__label {
			border-color: var(--color-primary-400);
			color: var(--color-primary-400);
		}

		.size_lbl,
		.gend_lbl {
			display: block;
			border: 2px solid var(--border-color-fade);
			border-radius: 30px;
			text-align: center;
			cursor: pointer;
			transition: background 0.2s, border 0.2s;
		}

		.size_txt,
		.gend_txt {
			padding: 0 1.5rem;
			font-size: 0.9rem;
			line-height: 2.5;
			transition: color 0.2s;
		}

		.data-selector input {
			display: none;
		}

		input:checked+.size_lbl,
		input:checked+.gend_lbl,
		input:checked+.pos_txt_lbl {
			border-color: var(--clr-1);
		}

		input:checked+.size_lbl *,
		input:checked+.gend_lbl *,
		input:checked+.pos_txt_lbl {
			color: var(--clr-1);
		}

		/* Switch preview */
		#prev-cont .lbl .inlb {
			padding-top: 3px;
			font-weight: inherit;
			font-size: inherit;
		}

		.prev-box {
			display: inline-block;
			width: 150px;
			max-width: 50%;
			padding: 0 5px 5px 0;
			box-sizing: border-box;
		}

		.prev-lbl {
			display: block;
			border: 3px solid #ebebeb;
			border-radius: 10px;
			text-align: center;
			box-sizing: border-box;
			cursor: pointer;
		}

		.prev-txt {
			padding: 0 1rem;
			color: #aaa;
			font-size: 0.9rem;
			line-height: 2.2;
		}

		.cbx:checked+.prev-lbl {
			border-color: var(--clr-1);
		}

		.cbx:checked+.prev-lbl .prev-txt {
			color: var(--clr-1);
			font-weight: bold;
		}

		/* Logos */
		.logo-field__list {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(2.5rem, 1fr));
			gap: 0.5rem;
		}

		.logo-field__item {
			--icon-size: 60%;
			background-color: var(--border-clr-400);
			border: 3px solid transparent;
			border-radius: 100%;
			aspect-ratio: 1;
			cursor: pointer;
			filter: grayscale(1);
			transition: border 0.2s, filter 0.2s;

			&:has(:checked) {
				border-color: var(--color-primary-400);
				filter: grayscale(0);
			}

			& img {
				border-radius: inherit;
			}
		}

		.logo-field__preview {
			position: relative;
			aspect-ratio: 2;
			display: none;
			overflow: hidden;
			border-radius: var(--border-radius-400);

			&.active {
				display: block;
			}
		}

		.logo-field__preview-wrapper {
			width: 200%;
			position: absolute;
			right: 0;
			bottom: 0;

			.item-type--tnkm & {
				bottom: -30%;
			}

			.item-type--swtm & {
				bottom: -20%;
			}

			.item-type--swtf & {
				bottom: -70%;
			}

			.item-type--hodf & {
				right: -30%;
				bottom: -70%;
			}

			.item-type--tnkf & {
				bottom: -30%;
				right: -30%;
			}

			.item-type--zipm & {
				bottom: -30%;
			}
		}

		.logo-field__preview-image {
			width: 100%;
		}

		.logo-field__preview-logo {
			display: none;
			position: absolute;
			aspect-ratio: 1;

			&.active {
				display: block;
			}

			.item-type--tshm & {
				width: 5%;
				bottom: 11%;
				right: 30%;
			}

			.item-type--tshf & {
				width: 5%;
				bottom: 13%;
				right: 32%;
			}

			.item-type--vts & {
				width: 5%;
				bottom: 13%;
				right: 32%;
			}

			.item-type--swtm & {
				width: 5%;
				bottom: 19%;
				right: 31%;
			}

			.item-type--swtf & {
				width: 5%;
				bottom: 29%;
				right: 31%;
			}

			.item-type--hodm & {
				width: 5%;
				bottom: 16%;
				right: 33%;
			}

			.item-type--hodf & {
				width: 5%;
				bottom: 22%;
				right: 38%;
			}

			.item-type--tnkm & {
				width: 5%;
				bottom: 20%;
				right: 32%;
			}

			.item-type--tnkf & {
				width: 5%;
				bottom: 20%;
				right: 37%;
			}

			.item-type--zipm & {
				width: 5%;
				bottom: 18%;
				right: 37%;
			}

			.item-type--zipf & {
				width: 5%;
				bottom: 15%;
				right: 37%;
			}
		}

		/* Position */
		.data-area {
			display: flex;
			flex-wrap: wrap;
			gap: 0.5rem;
		}

		.data-area>* {
			flex-grow: 1;
			text-align: center;
		}

		/* Pencil case back */
		.back_lbl {
			display: inline-block;
			background-size: 90%;
			background-position: center;
			background-repeat: no-repeat;
			border: 3px solid #aaa;
			width: 40px;
			height: 40px;
			margin: 0 4px 4px 0;
			box-sizing: border-box;
			cursor: pointer;
		}

		#cbx_back_1+.back_lbl {
			background-color: var(--clr-1);
		}

		.cbx:checked+.back_lbl {
			border-color: #006bdc;
		}

		#back_img_cont {
			display: none;
			border: 1px solid #ddd;
			width: 44%;
			min-width: 160px;
			padding-bottom: 30%;
		}

		#back_des {
			background-repeat: no-repeat;
			width: 100%;
			height: 100%;
			position: absolute;
		}

		/* Quantity */
		#qty_box {
			overflow: hidden;
		}

		#quantity {
			border: 1px solid #ccc;
			width: 2rem;
			outline: 0 none;
			text-align: center;
			line-height: 2rem;
			float: left;
			cursor: default;
		}

		#qty_box {
			width: 1.7rem;
			margin-left: 8px;
			float: left;
		}

		.qty_edit {
			background-color: #bbb;

			line-height: 1rem;
			color: #fff;
			text-align: center;
		}

		.qty_edit.on {
			background-color: #777;
			cursor: pointer;
		}

		.qty_edit.on:hover {
			background-color: #333;
		}

		#qty_pls {
			margin-bottom: 2px;
		}

		/* Cart button */
		#submit {
			font-size: min(1.1rem, 5vw);
		}

		/* Responsive */
		@media (max-width: 1024px) {
			.main-wrap {
				--wrap-width: 768px;
			}

			#item .content,
			#right-panel {
				grid-template-columns: minmax(0, 1fr);
			}

			#left-panel {
				max-width: 600px;
				margin-left: auto;
				margin-right: auto;
			}

			.type-slide .carousel {
				--columns: 2.4;
			}

			#img-zoom-icon {
				display: block;
			}

			#img-zoom {
				max-width: none;
				width: auto;
				height: 140%;
			}
		}

		@media (max-width: 480px) {
			#item {
				padding: 0;
			}

			#item-img {
				border-radius: 0 0 2.5rem 2.5rem;
				box-shadow: 0 40px 20px -40px rgba(0, 0, 0, 1);
			}

			#type-sec,
			#right-panel {
				padding-left: 2rem;
				padding-right: 2rem;
			}

			#type-sec {
				margin-bottom: -1.8rem;
				z-index: 1;
			}

			.type-box {
				padding: 0.5rem;
				padding-bottom: 1.8rem;
			}

			.type-img {
				box-shadow: 0 1.5rem 0.8rem -0.8rem rgba(0, 0, 0, 1);
			}

			.item-type-title {
				font-size: 1.2rem;
				padding-bottom: 10px;
			}
		}
	</style>
@endpushOnce

<x-templates.main>

	<div
		class="main-wrap pad-t-3 item-type--{{ $legacyProduct->type->slug }}"
		id="item"
		data-id="{{ $legacyProduct->id }}"
		data-hroot=""
	>

		<!-- Warning -->
		@if ($warning)
			<p
				id="news"
				class="mar-b-1 {{ $warning['color'] }}"
			>{{ $warning['text'] }}</p>
		@endif

		<form
			method="POST"
			class="content gap3 al-top grid"
		>
		</form>

		{{-- Left panel --}}
		<x-pages.product.left-panel
			:$legacyProduct
			:$relatedGarmentCount
			:$relatedGarments
			:$relatedAccessoryCount
			:$relatedAccessories
		/>

	</div>

</x-templates.main>
