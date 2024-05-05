document.addEventListener('DOMContentLoaded', async function () {
	const recapShippingElement = document.querySelector('.js-recap-shipping-price')
	const shippingCheckboxes = document.querySelectorAll('.js-shipping-method')

	shippingCheckboxes.forEach((element) => {
		element.addEventListener('change', async () => {
			const shippingMethodId = element.value

			recapShippingElement.dataset.shippingMethodId = shippingMethodId

			const orderId = element.dataset.cartId

			update_cart(orderId, { shipping_method_id: shippingMethodId })
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
	$('#code-inp').on('keyup', function () {
		if ($(this).val().length) {
			$('#add_code').show()
		} else {
			$('#add_code').hide()
		}
	})
})
