$(document).ready(function () {
	fix_height()

	$(window).resize(function () {
		fix_height()
	})

	function fix_height() {
		var head_height = $('#h-pan').outerHeight()
		$('header').css('height', head_height + 'px')
		$('#h-navM').css({ top: head_height + 'px', height: 'calc(100% - ' + head_height + 'px)' })
	}

	$('#h-navM').click(function () {
		$('#h-cbxM').click()
	})

	$('#h-cbxM').click(function () {
		$('body').toggleClass('fixed')
		$('#h-pan').toggleClass('h-open')
	})
})
