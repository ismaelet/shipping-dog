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
			//console.log(response)

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
