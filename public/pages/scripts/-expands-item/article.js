document.addEventListener('DOMContentLoaded', function () {
	const logoFieldSelector = '.js-logo'

	const id = $('#item').data('id')

	// fix height
	fix_height()
	change_logo()

	$(window).resize(function () {
		fix_height()
	})

	function fix_height() {
		if ($(window).width() > 1024) {
			$('#right-panel').css('min-height', $('#left-panel .wrap').outerHeight() + 'px')
		} else {
			$('#right-panel').css('min-height', '0')
		}
	}

	// change properties
	$('#color .cbx').on('change', function () {
		$('#color .cbx').prop('checked', false)
		$(this).prop('checked', true)

		change_image()
	})

	$('#size .cbx').on('change', function () {
		$('#size .cbx').prop('checked', false)
		$(this).prop('checked', true)

		update_price()
	})

	$('.js-change-position').on('change', function () {
		$('.js-change-position').prop('checked', false)
		$(this).prop('checked', true)

		change_image()
	})

	// get properties
	function get_colorId() {
		var colorId

		if ($('#color .cbx:checked').length) {
			colorId = $('#color .cbx:checked').data('id')
		} else {
			colorId = 0
		}

		return colorId
	}

	// get properties
	function get_position() {
		var position_id

		if ($('.js-change-position:checked').length) {
			position_id = parseInt($('.js-change-position:checked').val())
		} else {
			position_id = 1
		}

		if (position_id == 5) {
			$('#item').addClass('hide-logo')
		} else {
			$('#item').removeClass('hide-logo')
		}

		return position_id
	}

	/*
	 * Logos
	 */
	document.querySelectorAll(logoFieldSelector).forEach((element) => {
		element.addEventListener('change', () => {
			change_logo()
		})
	})

	function change_logo() {
		const logoInput = document.querySelector(logoFieldSelector + ':checked')
		const logo_id = get_logo_id()

		if (logoInput) {
			if (logo_id) {
				const logoImage = client_url('/uploads/shops/logos/' + logo_id + '.png')

				document.querySelector('.js-logo-image-background').classList.add('active')

				document.querySelectorAll('.js-logo-image').forEach((element) => {
					element.classList.add('active')
					element.src = logoImage
				})
			} else {
				document.querySelector('.js-logo-image-background').classList.remove('active')

				document.querySelectorAll('.js-logo-image').forEach((element) => {
					element.classList.remove('active')
				})
			}
		}

		update_price()
	}

	function get_logo_id() {
		const logoInput = document.querySelector(logoFieldSelector + ':checked')

		let logo_id = 0

		if (logoInput) {
			logo_id = parseInt(logoInput.value)
		}

		return logo_id
	}

	// pencilcase back
	$('#back_cont .cbx').on('change', function () {
		$('#back_cont .cbx').prop('checked', false)
		$(this).prop('checked', true)
		backID = $(this).val()

		change_back()
	})

	// type sliders
	$('.item-type-title').click(function () {
		const self = $(this)
		const panel = $(self.data('tar'))

		$('.item-type-title').removeClass('active')
		self.addClass('active')

		$('.type-slide').removeClass('active')
		panel.addClass('active')
	})

	// change view
	$('.img-position_id').mouseenter(function () {
		$('#item-img').css('background-image', $(this).css('background-image'))
	})

	// update image
	async function change_image() {
		const position_id = get_position()
		const colorId = get_colorId()

		let timeout

		timeout = setTimeout(function () {
			$('#img-load').fadeIn()
		}, 300)

		// change main image
		const url = (await get_class_properties('Ecommerce\\Product', 'clientFullImage', id, { colorId: colorId, position_id: position_id })).clientFullImage

		clearTimeout(timeout)

		$('.js-main-image').attr('src', url)

		// change image for the logos
		const urlFront = (await get_class_properties('Ecommerce\\Product', 'clientFullImage', id, { colorId: colorId })).clientFullImage

		$('.js-front-image').attr('src', urlFront)

		// hide the loading
		$('#img-load').fadeOut()
	}

	// update price
	async function update_price() {
		const colorId = get_colorId()
		const logo_id = get_logo_id()
		const fullPrice = (await get_class_properties('Ecommerce\\Product', 'fullPriceString', id, { colorId: colorId, logo_id: logo_id })).fullPriceString
		const price = (await get_class_properties('Ecommerce\\Product', 'priceString', id, { colorId: colorId, logo_id: logo_id })).priceString

		document.querySelectorAll('.js-price').forEach((element) => {
			element.innerHTML = price
		})

		document.querySelectorAll('.js-full-price').forEach((element) => {
			element.innerHTML = fullPrice
		})
	}

	// zoom in
	$('.img-zoom').click(function () {
		if ($(window).width() <= 1440) {
			$('#img-zoom-bgr').fadeIn()
			$('body').addClass('fixed')
		}
	})

	$('#img-zoom-bgr').click(function () {
		$(this).fadeOut()
		$('body').removeClass('fixed')
	})
})
