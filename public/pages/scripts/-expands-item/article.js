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
				get_logo(logo_id).then((response) => {
					const logoImage = response.image

					document.querySelector('.js-logo-image-background').classList.add('active')

					document.querySelectorAll('.js-logo-image').forEach((element) => {
						element.classList.add('active')
						element.src = logoImage
					})
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
	function change_image() {
		let timeout = setTimeout(function () {
			$('#img-load').fadeIn()
		}, 300)

		// change main image
		get_product(id, get_colorId(), get_position(), get_logo_id()).then((response) => {
			$('.js-main-image').attr('src', response.image)

			clearTimeout(timeout)
			$('#img-load').fadeOut()
		})

		// change image for the logos
		get_product(id, get_colorId(), null, get_logo_id()).then((response) => {
			$('.js-front-image').attr('src', response.image)
		})
	}

	// update price
	function update_price() {
		get_product(id, get_colorId(), get_position(), get_logo_id())
			.then((response) => {
				const priceString = response.priceString
				const fullPriceString = response.fullPriceString

				document.querySelectorAll('.js-price').forEach((element) => {
					element.innerHTML = priceString
				})

				document.querySelectorAll('.js-full-price').forEach((element) => {
					element.innerHTML = fullPriceString
				})
			})
			.catch((error) => {
				console.error(error)
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

/**
 * Retrieves the logo data from the server.
 *
 * @param {number} logoId - The ID of the logo.
 * @returns {Promise} A promise that resolves to the logo data.
 */
async function get_logo(logoId) {
	return axios
		.get(`/api/logos/${logoId}`)
		.then((response) => {
			return response.data
		})
		.catch((error) => {
			return error
		})
}

/**
 * Retrieves product information from the server.
 *
 * @param {number} productId - The ID of the product.
 * @param {number} colorId - The ID of the color.
 * @param {?number} positionId - The ID of the position.
 * @param {number} logoId - The ID of the logo.
 * @returns {Promise<Object>} - A promise that resolves to the product data.
 */
async function get_product(productId, colorId, positionId, logoId) {
	return axios
		.get(`/api/products/${productId}`, {
			params: {
				color_id: colorId,
				position_id: positionId,
				logo_id: logoId,
				image_size: 1024,
			},
		})
		.then((response) => {
			return response.data
		})
		.catch((error) => {
			return error
		})
}
