$(document).ready(function () {
	// sort list
	$('.js-sort-list').click(function () {
		$(this).submit()
	})

	$('.search-bar input').focus(function () {
		$(this).parents('.search-bar').addClass('active')
	})

	$('.search-bar input').blur(function () {
		$(this).parents('.search-bar').removeClass('active')
	})

	$('.js-search').click(function () {
		$(this).parents('form').submit()
	})

	$('.js-search-cancel').click(function () {
		$('.js-search-input').val('')
		$(this).parents('form').submit()
	})

	// pages
	$('.js-change-page').click(function () {
		$('.js-current-page').val($(this).data('page'))

		$(this).parent().find('button').click()
	})
})
