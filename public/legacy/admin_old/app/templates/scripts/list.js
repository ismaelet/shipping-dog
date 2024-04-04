$(document).ready(function() {

	$('#list .head .field.clickable').click(function() {
		$(this).submit()
	})

	$('.page').click(function() {

		$('#pageN').val($(this).data('page'))

		$(this).parent().find('button').click()

	})

	//search bar
	$('#search-bar .icon-box').click(function() {

		$(this).parent().find('button').click()

	})

	$('#search-bar .inp').focus(function() {

		$(this).parent().addClass('active')

	})

	$('#search-bar .inp').blur(function() {

		$(this).parent().removeClass('active')

	})

	//long inputs
	$('#list .text-field').hover(function() {

		const field = $(this)
		const textShort = field.find('.text-short')

		if (textShort[0].scrollWidth > textShort.innerWidth() + 1) {

			field.addClass('show-content')

		}

	}, function() {

		$(this).removeClass('show-content')

	})

})