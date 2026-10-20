@pushOnce('styles')
	<style>
		.flow-bar {
			color: var(--color-neutral-250);
		}

		.flow-line {
			border-color: currentColor;

			&:has(+ [data-active="true"]) {
				border-color: var(--color-primary-400);
			}
		}

		.flow-step {
			--icon-size: 45%;
			width: min(2rem, 8vw);

			svg {
				fill: currentColor;
			}

			&[data-active="true"] {
				color: var(--color-primary-400);
			}

			&[data-current-step="true"] {
				.flow-circle {
					background-color: currentColor;
				}

				.flow-number,
				svg {
					color: var(--color-neutral-light-100);
				}
			}
		}

		.flow-circle {
			border-color: currentColor;
		}

		.data_tbl {
			display: table;
			table-layout: fixed;
			border-collapse: collapse;
			width: 100%;
			text-align: left;
		}

		.data_row {
			display: table-row;
		}

		.data_col {
			display: table-column;
		}

		#items_panel {
			display: grid;
			gap: 1rem;
		}

		#items .row {
			grid-template-columns: 7rem auto 1rem;
			align-items: center;
			gap: 1rem;
		}

		#items .row img {
			display: block;
			border-radius: var(--border-radius-400);
		}

		.art_name {
			font-size: 0.9rem;
			letter-spacing: 0.01em;
		}

		.order-list__description {
			padding-top: 3px;
			font-size: var(--font-size-300);
		}

		.price {
			margin-top: 10px;
			font-size: 0.9rem;
			letter-spacing: 0.03em;
		}

		.price-old {
			color: #999;
			text-decoration: line-through;
		}

		.price-promo {
			color: #bb0e0e;
		}

		.art_warn {
			margin-top: 5px;
			color: #b90002;
		}

		#items_panel .remove {
			opacity: 0.4;
			background-color: transparent;
			border: none;
			aspect-ratio: 1 / 1;
			cursor: pointer;
			transition: opacity 0.4s;
		}

		#items_panel .remove:hover {
			opacity: 1;
		}

		#items_panel .remove svg {
			fill: var(--text-color-strong);
		}

		.bott_txt {
			margin-top: 1rem;
		}

		#count_sel {
			width: 250px;
			max-width: 100%;
			margin-top: 1rem;
		}

		#ship_cont {
			margin-block: 0.8rem;
		}

		.ship_row {
			display: grid;
			grid-template-columns: max-content minmax(0, 1fr);
			align-items: center;
			gap: 3rem;
			border-top: 1px solid var(--color-surface);
		}

		.ship_chck_box {
			padding-left: 1rem;
		}

		.inp_cbx {
			display: none;
		}

		.icon {
			border: 1px solid var(--color-surface-500);
			border-radius: 100%;
			width: 20px;
			height: 20px;
			transition: border 0.05s;
			cursor: pointer;
		}

		.inp_cbx:checked+.icon,
		.icon:hover {
			border-color: var(--color-neutral-300);
		}

		.icon-dot {
			display: none;
			background-color: var(--color-primary-400);
			border-radius: 100%;
			width: 50%;
			height: 50%;
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
		}

		.inp_cbx:checked+.icon .icon-dot {
			display: block;
		}

		.ship_box {
			display: table-cell;
			padding: 1rem 0;
			cursor: default;
		}

		.ship_box .art_name {
			font-size: 1rem;
		}

		.date {
			font-size: 0.9rem;
		}

		.ship_ico_box {
			display: table-cell;
			text-align: center;
			vertical-align: middle;
		}

		#chck_box {
			display: grid;
			gap: 1rem;
			padding: 1.5rem var(--spacing-content);
			background-color: var(--color-surface);
			border-radius: var(--border-radius-400);
			margin-top: 1rem;
			text-align: center;
		}

		.chck_item {
			border-bottom: 1px solid var(--border-color-strong);
			overflow: auto;
			font-size: 1rem;
		}

		.chck_name {
			color: var(--text-color-strong);
			float: left;
		}

		.chck_pric {
			float: right;
			color: var(--fc-700);
			font-family: var(--font-heading);
			font-size: var(--fs-400);
			letter-spacing: 0.05em;
		}

		#total .chck_name {
			font-size: 1rem;
		}

		#total .chck_pric {
			font-size: 20px;
		}

		#chck_btn_box {
			gap: 0.5rem;
			width: 100%;
			margin-top: 1rem;
		}

		.promo_txt {
			background-color: #ff9600;
			padding: 3px 0.8rem;
			margin-top: 1rem;
			color: #fff;
			font-size: 0.9rem;
		}

		#empty {
			border: 1px solid #ddd;
			padding: 4.5rem 1rem;
			margin: 20vh 2rem;
			color: #777;
			font-size: 0.9rem;
			letter-spacing: 0.03em;
			text-align: center;
		}

		@media (max-width: 1024px) {
			#content {
				grid-template-columns: 1fr;
			}
		}

		@media (max-width: 480px) {
			#flow-bar {
				margin-inline: 1rem;
			}
		}

		@media (min-width: 1024px) {
			.page-content {
				grid-template-areas:
					'guest login'
					'register login'
					'shop _';
			}

			.guest-panel {
				grid-area: guest;
			}

			.login-panel {
				grid-area: login;
			}

			.register-panel {
				grid-area: register;
			}

			.shop-panel {
				grid-area: shop;
			}
		}
	</style>
@endpushOnce

<x-layouts.cart>

	<div class="page-content grid items-start gap-8 lg:grid-cols-2 lg:gap-16">

		{{-- Guest --}}
		<form
			action="{{ route('cart.access.guest') }}"
			class="guest-panel cart-panel surface"
			method="POST"
		>
			@csrf

			<x-heading.component>Ordina senza registrarti</x-heading.component>
			<p>Clicca qui se vuoi procedere all'acquisto senza registrarti</p>
			<x-button
				type="submit"
				name="guest"
				size="small"
			>Ordina come ospite</x-button>
		</form>

		{{-- Login --}}
		<form
			class="login-panel cart-panel surface"
			method="POST"
			action="{{ route('login.auth', ['intended' => route('cart.address')]) }}"
		>
			@csrf

			<x-heading.component>Hai già un account?</x-heading.component>

			<x-inputs.group>
				<input
					type="hidden"
					name="redirect"
					value="true"
				>
				<x-input
					name="email"
					placeholder="Indirizzo e-mail"
				/>
				<x-input
					type="password"
					name="password"
					placeholder="Password"
				/>

				<div class="text-right">
					<p class=""><a href="{{ route('password.request') }}">Hai dimenticato la password?</a></p>
				</div>
			</x-inputs.group>

			@error('email')
				<x-alert type="error">{{ $message }}</x-alert>
			@enderror

			<div class="grid grid-cols-1 gap-2">
				<x-button
					type="submit"
					size="small"
				>
					Log In
				</x-button>

				<x-button
					href="{{ route('auth.google.redirect', ['intended' => route('cart.address')]) }}"
					variant="secondary"
					size="small"
				>
					<x-icon-google />
					Accedi con Google
				</x-button>
			</div>
		</form>

		{{-- Register --}}
		<div class="register-panel cart-panel surface">
			<x-heading.component>Registrati</x-heading.component>

			<p>Vai qui sotto se vuoi registrarti</p>

			<div class="grid grid-cols-1 gap-2">
				<x-button
					:href="route('register')"
					variant="secondary"
					size="small"
				>Registrati</x-button>

				<x-button
					href="{{ route('auth.google.redirect', ['intended' => route('cart.address')]) }}"
					variant="secondary"
					size="small"
				>
					<x-icon-google />
					Accedi con Google
				</x-button>
			</div>
		</div>

		{{-- Continue --}}
		<div class="shop-panel cart-panel surface">
			<x-heading.component>Continua con gli acquisti</x-heading.component>

			<p>Vuoi aggiungere altri articoli al carrello prima di continuare?</p>

			<x-button
				:href="route('shop')"
				variant="secondary"
				size="small"
			>Torna al negozio</x-button>
		</div>
	</div>
</x-layouts.cart>
