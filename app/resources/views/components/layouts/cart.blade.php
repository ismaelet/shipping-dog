@pushOnce('styles')
	<style>
		.component-heading {
		  font-size: var(--text-600);
		  padding-bottom: 0.5rem;
		  border-bottom: 1px solid var(--color-surface-500);
		}

		.cart-panel {
		  display: grid;
		  gap: 1.5rem;
		  padding: 1.5rem;
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

		.bott_txt {
		  margin-top: 1rem;
		}

		#count_sel {
		  width: 250px;
		  max-width: 100%;
		  margin-top: 1rem;
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

		.inp_cbx:checked + .icon,
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

		.inp_cbx:checked + .icon .icon-dot {
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
	</style>
@endpushOnce

<x-layouts.main>

	<div class="full-width bg-surface-500 py-20 text-center sm:py-24">
		<h1 class="text-800 tracking-widest">Ca<span class="txt-c1">rr</span>ello</h1>
	</div>

	<x-cart.flow-bar />

	<div class="content mb-20 grid grid-cols-1">
		{{ $slot }}
	</div>

</x-layouts.main>
