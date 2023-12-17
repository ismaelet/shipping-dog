$(document).ready(function () {
	// clear error messages
	$('.clear-error').keypress(function () {
		$('.inp.err').hide()
	})
	$('.clear-error').click(function () {
		$('.inp.err').hide()
	})

	// no spance inputs
	$('.inp-nospace').keypress(function (e) {
		if (e.which === 32) return false
	})

	// price inputs
	$('.inp-price input').blur(function () {
		let val = $(this).val()

		if (val !== '') {
			val = parseFloat(val)

			$(this).val(val.toFixed(2))
		}
	})

	/*
	 * Required checkboxes
	 */
	$('.js-required-cbx').on('change', function () {
		const self = $(this)
		self.parents('.js-cbx-container').find('.js-required-cbx').prop('checked', false)
		self.prop('checked', true)
	})

	// file upload
	$('.replace-img').click(function () {
		this.nextElementSibling.click()
	})

	$('.upload-on-change').on('change', function () {
		const form = this.closest('form')

		form.classList.add('loading')
		form.submit()
	})

	// fancy inputs
	$('.fancy-input .inp').on('input keyup', function () {
		const self = $(this)

		if (self.val()) {
			self.parent().removeClass('not-filled')
		} else {
			self.parent().addClass('not-filled')
		}
	})

	// dependent selector
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

	/*
	 * Ajax search
	 */
	$('body').on('keyup', '.ajax-search-text', async function (event) {
		const eventKey = event.key

		if (!['ArrowUp', 'ArrowDown'].includes(eventKey)) {
			// setup
			const self = $(this)
			const search = self.val()
			const parent = self.parents('.ajax-search')
			const parcialEndpoint = parent.data('endpoint')
			const parameters = {
				search: search,
			}

			parent.find('.ajax-search-box').html('')

			response = await AJAX('search-' + parcialEndpoint, parameters, 'json', false)

			if (response['error']) {
				console.log('API Error: ' + response['error'])
			} else if (response) {
				let results = response

				const count = Object.keys(results).length

				// if there are results
				if (count) {
					parent.find('.ajax-search-box').html('')

					// generate the rows with the results
					for (const [key, value] of Object.entries(results)) {
						let itemText = value['show-field']

						function makeBold(text, search) {
							let pattern = new RegExp('(' + search + ')', 'ig')
							return text.replace(pattern, '<strong>$1</strong>')
						}

						itemText = makeBold(itemText, search)

						parent
							.find('.ajax-search-box')
							.append('<p class="ajax-search-item" data-id="' + value['value-field'] + '" data-text="' + value['show-field'] + '" tabindex="-1">' + itemText + '</p>')
					}

					parent.find('.ajax-search-ddown').show()

					// if there aren't results
				} else {
					parent.find('.ajax-search-ddown').hide()
				}
			}
		}
	})

	$('.ajax-search').on('click', '.ajax-search-item', function () {
		var self = $(this)
		var parent = self.parents('.ajax-search')

		parent.find('.ajax-search-id').val(self.data('id')).change()
		parent.find('.ajax-search-text').val(self.data('text')).change()

		parent.find('.ajax-search-ddown').hide()
	})

	function hide_ajax_searcher(searcher) {
		const count = searcher.find('.ajax-search-item').length

		searcher.find('.ajax-search-ddown').hide()

		/*if (count == 1) {
			searcher.find('.ajax-search-id').val(searcher.find('.ajax-search-item').data('id'))
			searcher.find('.ajax-search-text').val(searcher.find('.ajax-search-item').data('text'))
		}*/
	}

	$(document).click(function () {
		$('.ajax-search').each(function () {
			hide_ajax_searcher($(this))
		})
	})

	$('.ajax-search').click(function (event) {
		event.stopPropagation()
	})

	$('.ajax-search-text').keydown(function () {
		$(this).parents('.ajax-search').find('.ajax-search-id').val(0)
	})

	$('body').on('focus', 'input', function () {
		if (!$(this).parents('.ajax-search').length) {
			$('.ajax-search').each(function () {
				hide_ajax_searcher($(this))
			})
		}
	})

	$('.ajax-search-text').keydown(function (event) {
		const searchBox = $(this).parents('.ajax-search')
		const eventKey = event.key

		if (eventKey == 'ArrowDown') {
			searchBox.find('.ajax-search-item').first().addClass('focused').focus()
		} else if (eventKey == 'ArrowUp') {
			searchBox.find('.ajax-search-item').last().addClass('focused').focus()
		}
	})

	$(document).on('keydown', '.ajax-search-item', function (event) {
		const eventKey = event.key
		const next = $(this).next()
		const prev = $(this).prev()

		event.preventDefault()

		if (eventKey == 'ArrowDown') {
			if (next.length) {
				$(this).removeClass('focused')
				next.addClass('focused').focus()
			}
		} else if (eventKey == 'ArrowUp') {
			if (prev.length) {
				$(this).removeClass('focused')
				prev.addClass('focused').focus()
			}
		} else if (eventKey == 'Enter') {
			$(this).click()
		}
	})

	/*
	 * Input submit
	 */
	$('.js-submit-on-change').on('change', function () {
		$(this).parents('form').submit()
	})
})
