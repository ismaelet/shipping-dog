$(document).ready(function () {
	//more collections
	$('#coll-more').click(function () {
		var ID = parseInt($('#seller_id').val())
		var numb = parseInt($('#collN').val())
		var max = parseInt($('#collBlock').val())
		var pos = parseInt($('#collPos').val())

		var i = 0

		while (i < max) {
			i++
			pos++

			$('#coll-box' + pos).removeClass('hidden')
		}

		$('#collPos').val(pos)
		if (pos >= numb) {
			$('#coll-more').hide()
		}
	})
})
