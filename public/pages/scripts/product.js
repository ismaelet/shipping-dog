document.addEventListener('DOMContentLoaded', function () {
	const logoFieldSelector = '.js-logo'

	const id = $('#item').data('id')
	const typeId = parseInt(document.getElementById('type-id').dataset.id)

	const primaryImage = document.querySelector('.js-primary-image')
	const secondaryImages = document.querySelectorAll('.js-secondary-image')
	const otherImages = document.querySelectorAll('.js-other-images img')

	// change properties
	$('[name="color_id"]').on('input', function () {
		change_image()
	})

	$('[name="size_id"]').on('input', function () {
		update_price()
	})

	$('[name="position_id"]').on('input', function () {
		change_image()
	})

	// get properties
	function get_color_id() {
		var color_id

		if ($('#color .cbx:checked').length) {
			color_id = $('#color .cbx:checked').data('id')
		} else {
			color_id = 0
		}

		return color_id
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

	// Change shown image
	otherImages.forEach((element) => {
		element.addEventListener('click', () => {
			primaryImage.src = element.src
		})
	})

	// update image
	function change_image() {
		const colorId = get_color_id()
		const logoId = get_logo_id()
		const positionId = get_position()

		// Show loader
		let timeout = setTimeout(function () {
			$('#img-load').fadeIn()
		}, 300)

		// change main image
		get_product(id, colorId, positionId, logoId).then((response) => {
			$('.js-main-image').attr('src', response.image)

			// Hide loader
			clearTimeout(timeout)
			$('#img-load').fadeOut()

			// Update secondary images
			update_secondary_images()
		})

		// change image for the logos
		get_product(id, colorId, null, logoId).then((response) => {
			$('.js-front-image').attr('src', response.image)
		})
	}

	/**
	 * Update the secondary images.
	 */
	function update_secondary_images() {
		const colorId = get_color_id()

		let positionId = get_position()

		positionId = positionId == 2 ? 1 : positionId

		secondaryImages.forEach((element) => {
			const secondaryPositionId = parseInt(element.dataset.positionId)

			if (secondaryPositionId === positionId) {
				element.dataset.active = 'false'
			} else {
				get_type_rasterized_image(typeId, colorId, secondaryPositionId).then((response) => {
					element.src = response.image
					element.dataset.active = 'true'
				})
			}
		})
	}

	// update price
	function update_price() {
		get_product(id, get_color_id(), get_position(), get_logo_id())
			.then((response) => {
				const customerPriceString = response.customerPriceString
				const fullPriceString = response.fullPriceString

				document.querySelectorAll('.js-price').forEach((element) => {
					element.innerHTML = customerPriceString
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
 * @param {number} color_id - The ID of the color.
 * @param {?number} positionId - The ID of the position.
 * @param {number} logoId - The ID of the logo.
 * @returns {Promise<Object>} - A promise that resolves to the product data.
 */
async function get_product(productId, color_id, positionId, logoId) {
	let parameters = {
		color_id: color_id,
		position_id: positionId,
		image_size: 1024,
	}

	if (logoId) {
		parameters.logo_id = logoId
	}

	return axios
		.get(`/api/products/${productId}`, {
			params: parameters,
		})
		.then((response) => {
			return response.data
		})
		.catch((error) => {
			console.log(error)
			return error
		})
}

async function get_type_rasterized_image(typeId, colorId, positionId) {
	let parameters = {
		color_id: colorId,
		position_id: positionId,
	}

	return axios
		.get(`/api/products/types/${typeId}/rasterized-image`, {
			params: parameters,
		})
		.then((response) => {
			return response.data
		})
		.catch((error) => {
			console.log(error)
			return error
		})
}
