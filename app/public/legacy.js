/*
 * Ajax call
 */
async function AJAX(parcialEndpoint, parameters = {}, contentType = 'json', debug = true) {
	let responseData

	// generate endpoint
	const root = document.querySelector('html').getAttribute('data-client-root')

	let endpoint = parcialEndpoint[0] == '/' ? root + parcialEndpoint : root + '/ajax/' + parcialEndpoint

	if (parameters) endpoint += '?' + new URLSearchParams(parameters)

	// send the request
	const response = await fetch(endpoint)

	// parse the response
	responseData = await response.text()

	switch (contentType) {
		case 'json':
			try {
				responseData = JSON.parse(responseData)
			} catch (err) {
				// console.log('Error: ' + responseData)
				responseData = null
			}
			break
		case 'text':
			responseData = responseData
			break
		default:
			responseData = null
	}

	// return the response
	return responseData
}

async function call_function(functionName, funParameters = null, debug = true) {
	let parameters = {}

	if (funParameters) {
		parameters.arguments = funParameters
	}

	return await AJAX('/old-api/call-function/' + functionName, parameters, 'json', true)
}

async function get_class_properties(className, propertyNames, objectId = null, setProperties = null, debug = true) {
	let parameters = {
		scope: propertyNames,
		objectId: objectId,
	}

	if (setProperties) {
		parameters.setProperties = format_set_request(setProperties)
	}

	return await AJAX('/old-api/get-class-properties/' + className, parameters, 'json', debug)
}

/*
 * Function
 */
function format_set_request(setProperties) {
	let setRequest = []

	if (setProperties) {
		for (const key in setProperties) {
			setRequest.push(key + '=' + setProperties[key])
		}
	}

	if (setRequest.length) {
		setRequest.join(',')
	} else {
		setRequest = null
	}

	return setRequest
}

function redirect(url) {
	const root = document.querySelector('html').getAttribute('data-client-root')

	window.location.href = root + url
}

function client_url(baseUrl) {
	const clientRoot = document.querySelector('html').getAttribute('data-client-root')
	const url = clientRoot + baseUrl

	return url
}

/*
 * Elements
 */
const activeClass = 'active'
const sidebardSelector = '.js-cart-sidebar'
const cartQuantitySelector = '.js-cart-quantity'
const cartDataQuantitySelector = '.js-cart-data-quantity'
const cartProductPriceSelector = '.js-cart-product-price'
const cartProductPriceStringSelector = '.js-cart-product-price-string'
const ordershippingFeeStringSelector = '.js-cart-shipping-price-string'
const cartPriceStringSelector = '.js-cart-price-string'

/*
 * Functions
 */
// update cart
async function update_cart(orderId, data) {
	// Update the order
	await patchOrder(orderId, data)

	// fetch data
	getOrder(orderId)
		.then((response) => {
			const cartData = response.data

			if (cartData) {
				// update cart icon
				if (parseInt(cartData.quantity)) {
					$('.h-cart-inline').removeClass('empty')
				} else {
					$('.h-cart-inline').addClass('empty')
				}

				// update elements content
				$(cartQuantitySelector).text(cartData.quantity)
				$(cartDataQuantitySelector).attr('data-quantity', cartData.quantity)
				$(cartProductPriceSelector).text(cartData.order_items_price)
				$(cartProductPriceStringSelector).text(cartData.gross_order_items_price_string)
				$(cartPriceStringSelector).text(cartData.price_string)

				// update shipping fee
				document.querySelectorAll('.js-cart-shipping-price-string').forEach((element) => {
					element.innerHTML = cartData.shipping_fee_string
				})

				if (!parseFloat(cartData.order_items_price)) {
					$(sidebardSelector).removeClass(activeClass)
				}

				// redirect if in a cart page and the cart is empty
				if (!cartData.quantity && $('[data-cart-page="true"]').length) {
					window.location.href = 'cart/empty'
				}
			}
		})
		.catch((error) => {
			console.error(orderId + ': ' + error)
		})
}

$(document).ready(function () {
	$('.file-inp').on('change', function () {
		$(this).parents('.file-form').find('.file-submit').click()
	})

	$('.file-form').submit(function () {
		var self = $(this)

		self.find('.file-cancel').hide()
		self.find('.file-submit').hide()
		self.find('.file-load').show()
		self.find('.file-stick').show().css('opacity', 1)
	})
})

$(document).ready(function () {
	function fix_pos(ele) {
		var offset

		var widMax = $(window).width()

		var right = ele.offset().left + ele.outerWidth()

		offset = Math.round(right - widMax + 16, 0)

		if (offset > 0) {
			ele.css('left', -offset + 'px')
		}
	}

	$('.tip-box').hover(
		function () {
			var ele = $(this).find('.tip-icon-txt')

			ele.addClass('active')
			fix_pos(ele)
		},
		function () {
			$(this).find('.tip-icon-txt').removeClass('active')
		}
	)

	$('.tip-icon').click(function () {
		$(this).next().addClass('active')
	})

	$('.tip-icon-txt').click(function () {
		$(this).removeClass('active')
	})
})

/**
 * Inputs
 */
const noSpaceSelector = '.js-input-no-space'

$(function () {
	//clear error messages
	$('.clear-error').keypress(function () {
		$('.inp.err').hide()
	})
	$('.clear-error').click(function () {
		$('.inp.err').hide()
	})

	//No spance inputs
	$('.inp-nospace').keypress(function (e) {
		if (e.which === 32) return false
	})

	//dependent selector
	$('.sel.affect').each(function () {
		selector_affect($(this))
	})
	$('.sel.affect').on('change', function () {
		selector_affect($(this))
	})

	function selector_affect(selector) {
		const affected = selector.parent().find('.sel[name="' + selector.data('affect') + '"]')
		const field_id = affected.data('id')
		const field_show = affected.data('show')

		affected.attr('disabled', false).empty()

		$.ajax({
			type: 'POST',

			url: affected.data('root') + '/system/modules/ajax/search-selector.php',

			data: { tbl: affected.data('tbl'), filter: affected.data('filter'), filter_val: selector.val(), field_id: field_id, field_show: field_show },

			success: function (data) {
				const options = $.parseJSON(data)

				for (var i = 0; i < options.length; i++) {
					const option = options[i]

					affected.append('<option value="' + option[field_id] + '" selected>' + option[field_show] + '</option>')
				}
			},
		})
	}

	//ajax search
	$('.ajax-search-txt').keyup(function () {
		//setup
		const self = $(this)
		const word = self.val()
		const parent = self.parents('.ajax-search')
		const tbl = parent.data('tbl')
		const nameId = parent.data('name-id')
		const nameShow = parent.data('name-show')
		const filter = parent.data('filter')
		const hroot = parent.data('hroot')

		let parameters = {
			config: filter + ';apiValue:' + word,
			fields: nameId + ';' + nameShow,
			apiField: nameShow,
			limit: 10,
		}

		parent.find('.ajax-search-box').html('')

		api_call(hroot, 'GET', tbl, false, parameters).then((response) => {
			if (response['res']) {
				let results = response['res']

				const count = Object.keys(results).length

				//if there are results
				if (count) {
					//generate the rows with the results
					for (const [key, value] of Object.entries(results)) {
						parent.find('.ajax-search-box').append('<p class="ajax-search-item" data-id="' + value[nameId] + '" data-text="' + value[nameShow] + '">' + value[nameShow] + '</p>')
					}

					parent.find('.ajax-search-ddown').show()

					//if there aren't results
				} else {
					parent.find('.ajax-search-ddown').hide()
				}
			}
		})
	})

	$('.ajax-search').on('click', '.ajax-search-item', function () {
		var self = $(this)
		var parent = self.parents('.ajax-search')

		parent.find('.ajax-search-id').val(self.data('id'))
		parent.find('.ajax-search-txt').val(self.data('text'))

		parent.find('.ajax-search-ddown').hide()
	})

	function hide_ajax_searcher(searcher) {
		const count = searcher.find('.ajax-search-item').length

		searcher.find('.ajax-search-ddown').hide()

		if (count == 1) {
			searcher.find('.ajax-search-id').val(searcher.find('.ajax-search-item').data('id'))
			searcher.find('.ajax-search-txt').val(searcher.find('.ajax-search-item').data('text'))
		}
	}

	$(document).click(function () {
		$('.ajax-search').each(function () {
			hide_ajax_searcher($(this))
		})
	})

	$('.ajax-search').click(function (event) {
		event.stopPropagation()
	})

	/**
	 * Inputs
	 */
	document.querySelectorAll(noSpaceSelector).forEach((element) => {
		element.addEventListener('keydown', (event) => {
			const eventKey = event.key

			if (eventKey == ' ') {
				event.preventDefault()

				return false
			}
		})
	})
})

$(window).ready(function () {
	//love button
	$('.love-btn[data-active="1"]').click(function () {
		//setup variabiles
		const self = $(this)
		const designID = self.data('id')
		const loved = self.data('loved')
		const hroot = self.data('hroot')
		const parameters = { designID: designID }

		if (loved) {
			api_call(hroot, 'DELETE', 'favorite', false, parameters).then((response) => {
				self.removeClass('active')
				self.data('loved', 0)
			})
		} else {
			api_call(hroot, 'POST', 'favorite', false, parameters).then((response) => {
				self.addClass('active')
				self.data('loved', 1)
			})
		}
	})
})

$(document).ready(function () {
	//open
	$('.lbox-open').click(function () {
		const tar = $($(this).data('tar'))

		if ($('.lbox.open').length) {
			$('.lbox').removeClass('open')
		} else {
			$('body').addClass('fixed')
		}

		tar.addClass('open')
	})

	//close
	$('.lbox').click(function (e) {
		if (e.target != this) return

		close()
	})

	$('.lbox-close').click(function () {
		close()
	})
	$('.lbox .close-icon').click(function () {
		close()
	})

	function close() {
		$('.lbox').removeClass('open')
		$('body').removeClass('fixed')
	}
})

$(window).on('load', function () {
	if ($('#load-cont').length) {
		setTimeout(function () {
			$('#load-cont').fadeOut(400)
			update()
		}, 400)
	} else {
		update()
	}

	var winBot = 0

	$(window).scroll(function () {
		update()
	})
	$(window).on('resize', function () {
		update()
	})

	function update() {
		winBot = $(window).scrollTop() + $(window).height()

		$('.load').each(function () {
			var elem = $(this)
			if (visible(elem)) {
				elem.removeClass('load')
			}
		})
	}

	function visible(elem) {
		var elemPos = elem.offset().top
		//var elemPos = elem.offset().top + elem.outerHeight(true);
		return elemPos <= winBot
	}
})

$(window).ready(function () {
	$('.love-btn.working').click(function () {
		//setup variabiles
		const self = $(this)
		const id = self.data('id')
		const loved = self.data('loved')
		const root = self.data('root')
		const hroot = self.data('hroot')
		const url = hroot + '/app/modules/ajax/love-button.php'

		//run
		$.ajax({
			method: 'POST',
			url: url,
			data: { id: id, loved: loved, root: root },

			success: function (data) {},
		}).done(function () {
			if (loved) {
				self.removeClass('active')
				self.data('loved', 0)
			} else {
				self.addClass('active')
				self.data('loved', 1)
			}
		})
	})
})

$(window).ready(function () {
	//minimum main height
	set_min_height()

	$(window).on('resize', function () {
		set_min_height()
	})

	function set_min_height() {
		const header = $('header')

		var height = header.outerHeight()

		if (header.css('position') != 'relative') {
			height = 0
		}
	}

	//theme management
	// if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
	// 	if (document.cookie.indexOf('color-scheme') == -1) {
	// 		switch_theme('dark')
	// 	}
	// }

	// $('.theme-switch .icon-box').click(function () {
	// 	const theme = $(this).data('color-scheme')
	// 	switch_theme(theme)
	// })

	// function switch_theme(theme) {
	// 	let expiration = new Date()

	// 	$('html').addClass('theme-transition')

	// 	$('html').attr('data-color-scheme', theme)

	// 	setTimeout(function () {
	// 		$('html').removeClass('theme-transition')
	// 	}, 500)

	// 	expiration.setMonth(expiration.getMonth() + 1)

	// 	document.cookie = 'color-scheme=' + theme + '; expires= ' + expiration + '; path=/'
	// }
})

$(document).ready(function () {
	//Click
	$('.scor-ele').click(function () {
		var scor = parseInt($(this).data('scor'))
		var group = $(this).parents('.scor-box')

		group.find('.scor-imp').val(scor)
	})

	//Hover
	$('.scor-ele').hover(
		function () {
			var scor = parseInt($(this).data('scor'))

			var group = $(this).parents('.scor-box')

			score(group, scor)
		},
		function () {
			var group = $(this).parents('.scor-box')
			var scor = parseInt(group.find('.scor-imp').val())

			score(group, scor)
		}
	)

	//Score
	function score(group, scor) {
		$(group)
			.find('.scor-ele')
			.each(function () {
				var ele_scor = parseInt($(this).data('scor'))

				if (ele_scor <= scor) {
					$(this).addClass('active')
				} else {
					$(this).removeClass('active')
				}
			})
	}
})

$(document).ready(function () {
	/*scroll down*/
	$('.scroll').on('click', function (e) {
		var target = $(this.getAttribute('href'))

		if (target.length) {
			e.preventDefault()

			$('html, body').stop().animate(
				{
					scrollTop: target.offset().top,
				},
				500
			)
		}
	})
})

$(document).ready(function () {
	$('.searchS-des').keyup(function () {
		var ele = $(this)
		var root = ele.data('root')

		$.ajax({
			type: 'POST',

			url: root + '/app/functions/search/designs.php',

			data: 'search=' + ele.val(),

			success: function (data) {
				$(ele).next().show()
				$(ele).next().find('.searchS-box').html(data)
			},
		})
	})

	$('.searchS-pan').on('click', '.searchS-item', function () {
		$(this).parents('.searchS-pan').prev().val($(this).data('id'))
		$(this).parents('.searchS-pan').hide()
	})
})

$(document).ready(function () {
	$('.searchS-store').keyup(function () {
		var element = this

		$.ajax({
			type: 'POST',
			url: '/system/general/php/search/shops.php',
			data: 'srch=' + $(this).val(),
			success: function (data) {
				$(element).next().show()
				$(element).next().find('.searchS-box').html(data)
			},
		})
	})

	$('.searchS-pan').on('click', '.searchS-item', function () {
		$(this).parents('.searchS-pan').prev().val($(this).data('id'))
		$(this).parents('.searchS-pan').hide()
	})

	$('.searcher').mouseleave(function () {
		$(this).find('.searchS-pan').hide()
	})
})

$(document).ready(function () {
	var itemN = $('#js-itemN').val()

	$('.itemN').html(itemN)
})

$(window).on('load', function () {
	$('.js-shop-load').fadeOut()

	//on type change
	$('.type-cbx').change(function () {
		$('.type-cbx').prop('checked', false)
		$(this).prop('checked', true)

		$('#page-inp').val(1)

		$('#filter-type').click()
	})

	$('.gender-cbx').change(function () {
		$('.gender-cbx').not(this).prop('checked', false)
	})

	//on tag change
	$('.tag-cbx').change(function () {
		$('.tag-cbx').prop('checked', false)
		$(this).prop('checked', true)

		$('#page-inp').val(1)

		$('#search-inp').val('')

		$('#filter_tag').click()
	})

	//on sorting change
	$('.sort-cbx').change(function () {
		$('.sort-cbx').prop('checked', false)
		$(this).prop('checked', true)

		$('#page-inp').val(1)

		$('#sort').click()
	})

	//remove type filter
	$('#remove-filter-type').click(function () {
		$('.type-cbx').prop('checked', false)
		$('.gender-cbx').prop('checked', false)

		$('#page-inp').val(1)

		$('#filter-type').click()
	})

	//remove tag filter
	$('#remove-filter-tag').click(function () {
		$('.tag-cbx').prop('checked', false)

		$('#page-inp').val(1)

		$('#filter_tag').click()
	})

	//remove type filter
	$('.page_txt').click(function () {
		if (!$(this).hasClass('disabled')) {
			var page = $(this).data('page')

			$('#page-inp').val(page)

			$('#page-btn').click()
		}
	})
})

$(document).ready(function () {
	update()

	$(window).resize(function () {
		update()
	})

	function update() {
		$('.slide-pan').each(function () {
			var panel = $(this)
			var block = panel.find('.slide-block').outerWidth()
			var box = panel.find('.slide-box').outerWidth()

			var size = Math.round(block / box)

			panel.data('size', size)

			panel.find('.slide-row').addClass('no-trans')

			slide(panel)

			panel[0].offsetHeight

			panel.find('.slide-row').removeClass('no-trans')
		})
	}

	//move panel
	function slide(panel) {
		//get variables
		var pos = parseInt(panel.data('pos'))
		var size = parseInt(panel.data('size'))

		//move the row
		var left = (-100 * (pos - 1)) / size

		panel.find('.slide-row').css({ left: left + '%' })
	}
})

$(document).ready(function () {
	//setup
	$('.carousel').each(function () {
		const panel = $(this)

		panel.find('.carousel-grid').addClass('no-trans')

		panel[0].offsetHeight

		panel.find('.carousel-grid').removeClass('no-trans')
	})

	//on click
	$('.carousel-arrow').click(function () {
		if (!$(this).hasClass('disabled')) {
			const arrow = $(this)
			const panel = $(arrow.data('tar'))

			update_position(arrow, panel)
			slide(panel)
		}
	})

	//on click
	$('.carousel-dot').click(function () {
		const self = $(this)
		const panel = $(self.parent().data('tar'))

		if (!self.hasClass('disabled')) {
			const span = Math.round(panel.css('--columns'))
			const page = self.data('page')

			let pos = span * (page - 1) + 1

			panel.data('pos', pos)

			self.parent().find('.carousel-dot').removeClass('active')
			self.addClass('active')

			slide(panel)
		}
	})

	//update position variable
	function update_position(arrow, panel) {
		if (arrow.data('pos')) {
			//if is a fixed arrow
			var pos = parseInt(arrow.data('pos'))
		} else {
			//if is a dynamic arrow
			const step = panel.css('--step') ? parseInt(panel.css('--step')) : 1
			const span = Math.round(panel.css('--columns'))
			const direction = arrow.data('direction')
			const loop = panel.data('loop') ? true : false
			const rows = parseInt(panel.css('--rows'))
			const count = panel.find('> *').length / rows
			const last = count - span + 1
			const posOld = panel.data('pos')

			var pos = posOld

			//update position
			if (direction === 'right') {
				pos += step
				arrow.prev('.left.disabled').removeClass('disabled')
			} else if (direction === 'left') {
				pos -= step
				arrow.next('.right.disabled').removeClass('disabled')
			}

			//disable arrows if the limit is reached
			if (pos <= 1 && !loop) {
				arrow.addClass('disabled')
			}
			if (pos >= last && !loop) {
				arrow.addClass('disabled')
			}

			//clamp position
			if (pos < 1) {
				if (loop && posOld <= 1) {
					pos = last
				} else {
					pos = 1
				}
			}

			if (pos > last) {
				if (loop && posOld >= last) {
					pos = 1
				} else {
					pos = last
				}
			}
		}

		//save the new position
		panel.data('pos', pos)
	}

	//move panel
	function slide(panel) {
		const rows = parseInt(panel.css('--rows'))
		const pos = rows > 1 ? parseInt(panel.data('pos')) * rows - 1 : parseInt(panel.data('pos'))
		const left = panel.find('div:nth-child(' + pos + ')').position().left + panel.scrollLeft()

		panel.scrollLeft(left)
	}
})

$(document).ready(function () {
	$('.svg').each(function () {
		var img = $(this)
		var imgURL = img.attr('src')
		var attributes = img.prop('attributes')

		$.get(
			imgURL,
			function (data) {
				// Get the SVG tag, ignore the rest
				var svg = jQuery(data).find('svg')

				// Loop through IMG attributes and apply on SVG
				$.each(attributes, function () {
					svg.attr(this.name, this.value)
				})

				// Replace IMG with SVG
				img.replaceWith(svg)
			},
			'xml'
		)
	})
})

/**
 * * Axios
 */
function getOrder(orderId) {
	return axios
		.get(`/api/orders/${orderId}`)
		.then((response) => {
			return response.data
		})
		.catch((error) => {
			return error
		})
}

function patchOrder(orderId, data) {
	return axios
		.patch(`/api/orders/${orderId}`, data)
		.then((response) => {
			return response.data
		})
		.catch((error) => {
			return error
		})
}
