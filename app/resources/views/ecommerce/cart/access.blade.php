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
			line-height: 1.5;
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
			border: 1px solid var(--surface-secondary);
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
			padding: 1.5rem var(--content-padding);
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
			font-family: var(--ff-titles);
			font-size: var(--fs-400);
			letter-spacing: 0.05em;
		}

		#total .chck_name {
			font-size: 1rem;
		}

		#total .chck_pric {
			font-size: 20px;
		}

		#total h1 {
			line-height: 1.5;
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

		.access-panel {
			display: grid;
			gap: 1.5rem;
			padding: 1.5rem;
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

<x-layouts.main>

	<section class="bg-surface-500 stack-content grid justify-items-center">
		<x-typography.section-heading class="py-24">
			Ca<span class="text-primary">rr</span>ello
		</x-typography.section-heading>
	</section>

	<section class="flow-bar mb-20 mt-16 flex w-full items-center justify-center px-8 md:px-16">

		@foreach (config('ecommerce.cart.steps') as $step => $stepData)
			{{-- Divisor --}}
			@if ($step > 1)
				<div class="flow-line grow border-b"></div>
			@endif

			{{-- Step --}}
			<div
				class="flow-step relative cursor-default"
				data-active="{{ $step <= $orderStep ? 'true' : 'false' }}"
				data-current-step="{{ $step == $pageStep ? 'true' : 'false' }}"
			>

				{{-- Number --}}
				<a
					{!! $step <= $orderStep && $step != $pageStep && !($stepData['tag'] == 'access' && Auth::check())
					    ? 'href="' . route('cart.' . $stepData['tag']) . '"'
					    : '' !!}
					class="flow-circle flex aspect-square rounded-full border"
				>

					@if ($step < $orderStep)
						{!! embed_icon('correct') !!}
					@else
						<p class="flow-number m-auto">{{ $step }}</p>
					@endif

				</a>

				{{-- Text --}}
				<p class="flow-text absolute left-1/2 mt-2 -translate-x-1/2 text-xs sm:text-base">{{ $stepData['label'] }}</p>

			</div>
		@endforeach

	</section>

	<div class="page-content mb-20 grid items-start gap-8 lg:grid-cols-2 lg:gap-16">

		{{-- Guest --}}
		<form
			action="{{ route('cart.access.guest') }}"
			class="guest-panel access-panel surface"
			method="POST"
		>
			<x-typography.component-heading>Ordina senza registrarti</x-typography.component-heading>
			<p>Clicca qui se vuoi procedere all'acquisto senza registrarti</p>
			<x-button
				type="submit"
				name="guest"
				size="small"
			>Ordina come ospite</x-button>
		</form>

		{{-- Login --}}
		<form
			class="login-panel access-panel surface"
			method="POST"
			action="{{ route('login.auth') }}"
		>
			<x-typography.component-heading>Hai già un account?</x-typography.component-heading>

			<x-inputs.group>
				<input
					type="hidden"
					name="redirect"
					value="true"
				>
				<x-inputs.input
					name="login"
					placeholder="Username o e-mail"
				/>
				<x-inputs.input
					type="password"
					name="password"
					placeholder="Password"
				/>
			</x-inputs.group>

			@error('login')
				<x-inputs.warning>{{ $message }}</x-inputs.warning>
			@enderror

			<x-button
				type="submit"
				size="small"
			>
				Log In
			</x-button>

			<div class="text-right">
				<p class=""><a href="{{ route('auth.recover') }}">Hai dimenticato la password?</a></p>
			</div>
		</form>

		{{-- Register --}}
		<div class="register-panel access-panel surface">
			<x-typography.component-heading>Registrati</x-typography.component-heading>

			<p>Vai qui sotto se vuoi registrarti</p>

			<x-button
				:href="route('register')"
				variant="secondary"
				size="small"
			>Registrati</x-button>
		</div>

		{{-- Continue --}}
		<div class="shop-panel access-panel surface">
			<x-typography.component-heading>Continua con gli acquisti</x-typography.component-heading>

			<p>Vuoi aggiungere altri articoli al carrello prima di continuare?</p>

			<x-button
				:href="route('shop')"
				variant="secondary"
				size="small"
			>Torna al negozio</x-button>
		</div>

	</div>

</x-layouts.main>
