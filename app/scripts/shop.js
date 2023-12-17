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
