$(document).ready(function () {
	//collections panel
	$('.col-cell').click(function () {
		col_slide($(this))
	})

	$('.col-arrow').click(function () {
		col_slide($(this))
	})

	function col_slide(ele) {
		var pos = ele.data('pos')
		var pan = ele.parents('.col-pan')

		if (pos === 0) {
			pan.removeClass('right').addClass('left')
		} else if (pos === 2) {
			pan.removeClass('left').addClass('right')
		}

		pan.data('pos', pos)
	}

	//shops panel
	$('#stor-sec .slide-arrow').click(function () {
		var arrow = $(this)
		var panel = $(arrow.data('tar'))
		var pos = parseInt(panel.data('pos')) + 1

		$('.stor-ele:not(.disabled)').addClass('disabled')
		$('.stor-ele .stor-btn').prop('disabled', true)
		$('#stor-ele' + pos).removeClass('disabled')
		$('#stor-ele' + pos + ' .stor-btn').prop('disabled', false)
	})
})
