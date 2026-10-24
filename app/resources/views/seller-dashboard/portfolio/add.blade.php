@pushOnce('styles')
	<style>
		/* Errors */
		#setup-sec .error {
			display: none;
		}

		/* Setup */
		#setup-sec {
			display: none;
		}

		#upload-box {
			box-shadow: 0 10px 20px -10px rgba(0, 0, 0, 0.4);
			width: 30rem;
			max-width: 100%;
			aspect-ratio: 1 / 1;
			transition: background 0.2s;
		}

		#upload-box:hover {
			background-color: var(--color-primary-400);
		}

		#upload-icon {
			width: 7rem;
			height: 7rem;
			transition: transform 0.2s;
		}

		#upload-box:hover #upload-icon {
			transform: translateY(-10px);
		}

		#upload-title {
			letter-spacing: 0.2rem;
			transition: color 0.2s;
		}

		#upload-box:hover #upload-title {
			color: #fff;
		}

		/* Data */
		#data-form {
			display: none;
		}

		#data-form input[name='file'] {
			display: none;
		}

		.error #data-form input[name='file'] {
			display: block;
		}

		#preview {
			box-shadow: 0 10px 30px -7px rgba(0, 0, 0, 0.2);
			max-width: 100%;
			max-height: 20rem;
		}

		#prev-over {
			opacity: 0;
			background-color: rgba(0, 0, 0, 0.6);
			transition: opacity 0.1s;
		}

		#prev-over:hover {
			opacity: 1;
		}

		#prev-icon {
			background-size: contain;
			width: 4rem;
			height: 4rem;
			transform: translate(0, 2rem);
			transition: transform 0.2s;
		}

		#prev-over:hover #prev-icon {
			transform: translate(0, 0);
		}

		/* Responsive */
		@media (max-width: 480px) {
			#setup-sec {
				padding-top: 2rem !important;
			}

			#upload-title {
				font-size: 1.5rem !important;
			}
		}
	</style>
@endpushOnce

@pushOnce('scripts')
	<script defer>
		$(document).ready(function() {
			change($('#pan').data('stage'))

			$('#upload-box').click(function() {
				$('.file[name=file]').click()
			})

			$('#prev-over').click(function() {
				$('.file[name=file]').click()
			})

			function get_path() {
				var input = $('.file')[0]

				if (input) {
					if (input.files && input.files[0]) {
						var reader = new FileReader()

						$('#preview-box').removeClass('hide')

						reader.onload = function(e) {
							$('#preview').attr('src', e.target.result)
						}

						reader.readAsDataURL(input.files[0])
					}
				}
			}

			$('.file[name=file]').change(function() {
				const acceptedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'png', 'webp']

				const extension = $(this).val().split('.').pop().toLowerCase()

				if (acceptedExtensions.includes(extension)) {
					change(2)
				} else {
					$('p.error').show()
					$('.wrong-extension').html(extension.toUpperCase())
				}
			})

			function change(stage) {
				if (stage == 1) {
					$('#setup-sec').show()
					$('#data-form').hide()
				} else if (stage == 2) {
					$('#setup-sec').hide()
					$('#data-form').show()
				}

				get_path()
			}
		})
	</script>
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
				class="center-items clickable inlb mar-v-2 mar-h-auto bg-surface-400 text-neutral-heading transition-colors hover:text-neutral-950"
			>

				<div class="grid-flow center-content">
					<div
						id="upload-icon"
						class="icon-box img"
					>
						<x-icon-upload />
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

			<x-heading.component>Carica una grafica</x-heading.component>

			<form
				action="{{ route('designs.store') }}"
				class="form file-form"
				method="POST"
				enctype="multipart/form-data"
			>
				@csrf

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
									<x-icon-upload />
								</div>
							</div>
						</div>
					</div>

					<?php generate_form($inputs, false, $error); ?>

					<x-checkbox
						name="cbx"
						text="Dichiaro di aver letto e accettato le <a
							class='text-link'
							href='{{ route('support.upload-terms') }}'>condizioni di invio</a>"
						:required="true"
					/>

					<div class="form-foot flex justify-center">

						<x-button
							type="submit"
							name="submit"
							class="file-submit"
						>
							<x-icon-upload />
							Carica la grafica
						</x-button>

						<div class="file-load"><x-icon-loading /></div>

					</div>

				</div>
			</form>
		</div>
	</div>
</x-layouts.seller-dashboard>
