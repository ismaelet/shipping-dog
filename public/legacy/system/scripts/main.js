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
	if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
		if (document.cookie.indexOf('theme-color') == -1) {
			switch_theme('dark')
		}
	}

	$('.theme-switch .icon-box').click(function () {
		const theme = $(this).data('theme')

		switch_theme(theme)
	})

	function switch_theme(theme) {
		let expiration = new Date()

		$('html').addClass('theme-transition')

		$('html').attr('data-theme', theme)

		setTimeout(function () {
			$('html').removeClass('theme-transition')
		}, 500)

		expiration.setMonth(expiration.getMonth() + 1)

		document.cookie = 'theme-color=' + theme + '; expires= ' + expiration + '; path=/'
	}
})
