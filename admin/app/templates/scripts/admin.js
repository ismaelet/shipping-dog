$(document).ready(function () {
	$('.js-open-menu-item').on('click', function () {
		const category = $(this).parents('.menu-item')

		toggle_category(category)
	})

	$('.js-open-menu').on('click', function () {
		open_menu()
	})

	$('.js-toggle-menu').on('click', function () {
		toggle_menu()
	})

	$('.js-open-menu').on('mouseenter', function () {
		open_menu()
	})

	$('.js-menu').on('mouseleave', function () {
		close_menu()
	})

	function open_menu() {
		$('.js-menu').addClass('open')
	}

	function close_menu() {
		$('.js-menu').removeClass('open')
	}

	function toggle_menu() {
		const menu = $('.js-menu')

		if (!menu.hasClass('open')) {
			open_menu()
		} else {
			close_menu()
		}
	}

	function open_category(category) {
		category.addClass('active')

		$('.menu-item')
			.not(category)
			.each(function () {
				close_category($(this))
			})
	}

	function close_category(category) {
		category.removeClass('active')
	}

	function toggle_category(category) {
		if (!category.hasClass('active')) {
			open_category(category)
		} else {
			close_category(category)
		}
	}
})
