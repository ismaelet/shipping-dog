document.addEventListener('DOMContentLoaded', async function () {
	const recapShippingElement = document.querySelector('.js-recap-shipping-price')
	const shippingCheckboxes = document.querySelectorAll('.js-shipping-method')

	shippingCheckboxes.forEach((element) => {
		element.addEventListener('change', async () => {
			const shippingMethodId = element.value

			recapShippingElement.dataset.shippingMethodId = shippingMethodId

			call_class_method('Ecommerce\\Cart', 'updateShippingMethodId', [shippingMethodId], await cartId())

			update_cart()
		})
	})
})

/*
 * OLD
 */
$(function () {
	/*
	 * Shipping
	 */
	$('.js-shipping-method').on('change', function () {
		const self = $(this)
		const cartId = parseInt(self.data('cart-id'))
		const shippingMethodId = parseInt(self.val())
		const shipped = parseInt(self.data('shipped'))
		const addressForm = $('.address-form')
		const addressInputs = $('.delivery-input')
		const localDeliveryClass = 'address-form--local-delivery'

		if (shipped) {
			addressInputs.prop('required', true)
			addressForm.removeClass(localDeliveryClass)
		} else {
			addressInputs.prop('required', false)
			addressForm.addClass(localDeliveryClass)
		}

		update_cart(cartId, { shippingMethodId: shippingMethodId })
	})

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
