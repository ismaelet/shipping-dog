$(document).ready(function () {
	$('.js-default-color-label').click(function () {
		$(this).parents('.js-color').find('.js-default-color').click()
	})

	$('.js-active-color').click(function () {
		if ($('.js-active-color:checked').length < 1) {
			$(this).prop('checked', true)
		}
	})

	$('.js-active-color-label').click(function () {
		$(this).parents('.js-color').find('.js-active-color').click()
	})

	$('.js-upload').click(function () {
		$(this).next().click()
	})

	$('.js-submit-upload').on('change', function () {
		$(this).next().click()
	})
})
