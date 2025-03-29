document.addEventListener('DOMContentLoaded', async function () {
	const recapShippingElement = document.querySelector('.js-recap-shipping-price')
	const shippingCheckboxes = document.querySelectorAll('.js-shipping-method')
	const shippingFields = document.querySelectorAll('[data-shipping-field]')

	shippingCheckboxes.forEach((element) => {
		element.addEventListener('change', async () => {
			const shippingMethodId = element.value

			recapShippingElement.dataset.shippingMethodId = shippingMethodId

			const orderId = element.dataset.cartId

			update_cart(orderId, { shipping_method_id: shippingMethodId })

			if (shippingMethodId == '11') {
				shippingFields.forEach((field) => {
					field.classList.add('hidden')
					field.querySelector('input, textarea').required = false
				})
			} else {
				shippingFields.forEach((field) => {
					field.classList.remove('hidden')
					field.querySelector('input, textarea').required = true
				})
			}
		})
	})
})

/*
 * OLD
 */
$(function () {
	/*
	 * Payments
	 */
	$('.pay_cbx').on('change', function () {
		$('.pay_cbx').not(this).prop('checked', false)
		$(this).prop('checked', true)
	})

	/*
	 * Coupons
	 */
	$('#code-inp').on('input', function () {
		if ($(this).val().length) {
			$('#add_code').show()
		} else {
			$('#add_code').hide()
		}
	})
})
