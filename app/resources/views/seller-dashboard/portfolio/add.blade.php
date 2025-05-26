@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/portfolio-add.css') }}"
	>
@endpushOnce

@pushOnce('scripts')
	<script
		defer
		src="{{ asset('js/legacy/portfolio-add.js') }}"
	></script>
@endpushOnce

<x-layouts.seller-dashboard>
	<div
		id="pan"
		class="<?= $error ? 'error' : '' ?>"
		data-stage="<?= $error ? 2 : 1 ?>"
	>

		<!-- Step 1: choose an image -->
		<section
			id="setup-sec"
			class="pad-v-5 pad-h-2 surface dashboard-panel text-center"
		>

			<div
				id="upload-box"
				class="center-items clickable inlb mar-v-2 mar-h-auto bg-surface-400 text-neutral-700 transition-colors hover:text-neutral-950"
			>

				<div class="grid-flow center-content">
					<div
						id="upload-icon"
						class="icon-box img"
					>
						{!! embed_icon('upload') !!}
					</div>

					<p class="text-600 font-heading font-semibold">Carica una nuova grafica</p>
				</div>

			</div>

			<div
				id="setup-data"
				class="mar-h-auto max-w-[50ch]"
			>
				<p class="error mar-b-2">Il file deve essere in <span class="font-semibold">PNG</span>. Il formato <span
						class="wrong-extension font-semibold"
					></span> non è accettato.</p>
				<h1
					id="setup-stitle"
					class="txt-15rem mar-b-1 font-semibold"
				>Requisiti del file</h1>
				<p
					id="setup-txt"
					class="mar-h-auto"
				>Il file deve essere un <strong>PNG</strong> con sfondo trasparente dove non vuoi che stampiamo. Raccomandiamo che
					il
					lato minore sia di almeno <strong>1000px</strong>.<br><br>
					Ricordiamo inoltre bordi con ombreggiature e sfumature di ogni tipo hanno una resa ottimale <strong>SOLO</strong>
					su
					capi bianchi, quindi consigliamo grafiche dai <strong>bordi netti</strong> per risaltare al meglio sui capi scuri.
				</p>
			</div>

		</section>

		<!-- Step 2: fill in the data -->
		<div id="data-form">

			<x-typography.component-heading>Carica una grafica</x-typography.component-heading>

			<form
				action="{{ route('designs.store') }}"
				class="form file-form"
				method="POST"
				enctype="multipart/form-data"
			>
				{{-- @csrf --}}

				<div class="gap1 pad-t-2 grid">

					<div
						id="preview-box"
						class="mar-b-2 <?php if ($error) {
						    echo 'hidden';
						} ?> text-center"
					>
						<div class="inlb relative">
							<img
								id="preview"
								class="inlb"
								src="#"
								alt=""
							>

							<div
								id="prev-over"
								class="bgr clickable grid place-items-center text-white"
							>
								<div
									id="prev-icon"
									class="place-center"
								>
									{!! embed_icon('upload') !!}
								</div>
							</div>
						</div>
					</div>

					<?php generate_form($inputs, false, $error); ?>

					<x-checkbox
						name="cbx"
						text="Dichiaro di aver letto e accettato le <a
							class='text-link'
							href='{{ client_url('/infos/contract') }}'>condizioni di invio</a>"
						:required="true"
					/>

					<div class="form-foot flex justify-center">

						<x-button
							type="submit"
							name="submit"
							class="file-submit"
						>
							{!! embed_icon('upload') !!}
							Carica la grafica
						</x-button>

						<div class="file-load">{!! embed_icon('loading') !!}</div>

					</div>

				</div>
			</form>
		</div>
	</div>
</x-layouts.seller-dashboard>
