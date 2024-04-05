/*
 * Elements
 */
const activeClass = 'active'
const sidebardSelector = '.js-cart-sidebar'
const cartQuantitySelector = '.js-cart-quantity'
const cartDataQuantitySelector = '.js-cart-data-quantity'
const cartProductPriceSelector = '.js-cart-product-price'
const cartProductPriceStringSelector = '.js-cart-product-price-string'
const cartShippingPriceStringSelector = '.js-cart-shipping-price-string'
const cartPriceStringSelector = '.js-cart-price-string'

/*
 * Functions
 */
// get cart ID
async function cartId() {
	return await call_function('Ecommerce\\cartId')
}

// update cart
async function update_cart_old() {
	// fatch data
	const cartData = await get_class_properties('Ecommerce\\Cart', ['quantity', 'productPrice', 'productPriceString', 'priceString'], await cartId(), null, false)

	// update cart icon
	if (parseInt(cartData.quantity)) {
		$('.h-cart-inline').removeClass('empty')
	} else {
		$('.h-cart-inline').addClass('empty')
	}

	// update elements content
	$(cartQuantitySelector).text(cartData.quantity)
	$(cartDataQuantitySelector).attr('data-quantity', cartData.quantity)
	$(cartProductPriceSelector).text(cartData.productPrice)
	$(cartProductPriceStringSelector).text(cartData.productPriceString)
	$(cartPriceStringSelector).text(cartData.priceString)

	if (!parseFloat(cartData.productPrice)) {
		$(sidebardSelector).removeClass(activeClass)
	}

	update_shipping()

	// redirect if in a cart page and the cart is empty
	if (!cartData.quantity && $('[data-cart-page="true"]').length) {
		window.location.href = 'cart/empty'
	}
}

// update shipping
function update_shipping() {
	const shippingElements = document.querySelectorAll(cartShippingPriceStringSelector)

	shippingElements.forEach(async (element) => {
		const shippingMethodId = element.dataset.shippingMethodId
		const shippingPrice = (await get_class_properties('Ecommerce\\ShippingMethod', 'priceString', shippingMethodId)).priceString

		element.textContent = shippingPrice
	})
}

/*
 * events
 */
document.addEventListener('DOMContentLoaded', async function () {
	const orderSelector = '.js-cart-order'
	const closeSidebardSelector = '.js-close-cart-sidebar'
	const addToCartSelector = '.js-add-to-cart'
	const removeFromCartSelector = '.js-remove-from-cart'
	const addToWishlistSelector = '.js-add-to-wishlist'
	const orderListSelector = '.js-order-list'

	/*
	 * Add to cart
	 */
	$(addToCartSelector + ':not(:disabled)').on('click', async function () {
		const self = $(this)
		const productId = self.data('product-id')
		const quantity = self.data('quantity')
		const fixedPrice = false
		//const variationInput = self.parents('form').find('[name="variation"]')
		//const variation = variationInput.length ? variationInput.val() : 0

		const parameters = {
			method: 'add',
			productId: productId,
			quantity: quantity,
			fixedPrice: fixedPrice,
			//variation: variation,
		}

		// vendor parameters
		/*
		if (typeof vendorEvents !== 'undefined') {
			if (typeof vendorEvents.meta.pixel.InitiateCheckout !== 'undefined') {
				parameters.vendorMetaPixelInitiateCheckoutEventId = vendorEvents.meta.pixel.InitiateCheckout.eventId
			}
			if (typeof vendorEvents.meta.pixel.AddToCart !== 'undefined') {
				parameters.vendorMetaPixelAddToCartEventId = vendorEvents.meta.pixel.AddToCart.eventId
			}
		}
		*/

		// add order
		await AJAX('ecommerce-orders', parameters, null, false)

		// populate and show the cart dropdown
		const orderList = await call_class_method('Ecommerce\\ViewManager', 'renderOrderList')

		update_cart_old()

		$(sidebardSelector).find(orderListSelector).html(orderList)
		$(sidebardSelector).addClass(activeClass)

		// vendor events
		if (typeof vendor_meta_pixel_initiatecheckout !== 'undefined') {
			vendor_meta_pixel_initiatecheckout()
		}
		if (typeof vendor_meta_pixel_addtocart !== 'undefined') {
			vendor_meta_pixel_addtocart_old()
		}
	})

	/*
	 * Add to favorites
	 */
	$(addToWishlistSelector).on('click', async function () {
		const self = $(this)
		const logged = self.data('logged')

		if (logged) {
			const module = self.parents('.js-wishlist-module')
			const itemId = self.data('item-id')
			const apiClass = 'Wishlist'
			const willBeAdded = parseInt(self.data('added')) ? 0 : 1
			const method = willBeAdded ? 'PUT' : 'DELETE'

			const parameters = {
				itemId: itemId,
			}

			await API(apiClass, method, parameters, false)

			self.data('added', willBeAdded)
			module.toggleClass('added')
		} else {
			redirect_old('/login?action=wishlist')
		}
	})

	/*
	 * Remove from cart
	 */
	$(document).on('click', removeFromCartSelector, async function () {
		const self = $(this)
		const parameters = {
			method: 'delete',
			orderId: self.data('order-id'),
		}

		// delete the order
		await AJAX('ecommerce-orders', parameters, 'none', true)

		// update the price
		update_cart_old()

		self.parents(orderSelector).remove()
	})

	/*
	 * Cart sidebar
	 */
	$(closeSidebardSelector).on('click', function () {
		$(sidebardSelector).removeClass(activeClass)
	})
})
