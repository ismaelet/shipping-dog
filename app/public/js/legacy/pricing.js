$(document).ready(function () {
	$('.price_inp').change(function () {
		update_price($(this))
	})
	$('.price_inp').keyup(function () {
		update_price($(this))
	})

	function update_price(ele) {
		var gain_w, gain_c

		var parent = ele.parents('.price_box')

		var price = parseFloat(ele.val() ?? 0)
		var price_def = parseFloat(parent.find('.price_def').val() ?? 0)
		var gain_w_def = parseFloat(parent.find('.gain_w_def').val() ?? 0))
		var gain_c_def = parseFloat(parent.find('.gain_c_def').val() ?? 0))

		var base_w = price_def - gain_w_def / 0.75
		var base_c = price_def - gain_c_def / 0.75

		if (!price) {
			price = 0.01
		} else if (price < 0) {
			price = 0.01
		}

		gain_w = (price - base_w) * 0.75
		gain_c = (price - base_c) * 0.75

		if (gain_w < 0) {
			gain_w = 0
		}
		if (gain_c < 0) {
			gain_c = 0
		}

		gain_w = gain_w.toFixed(2)
		gain_c = gain_c.toFixed(2)

		parent.find('.gain_w').val(gain_w + ' €')
		parent.find('.gain_c').val(gain_c + ' €')
	}

	$('.price_inp').blur(function () {
		var price = parseFloat($(this).val())
		var min = parseFloat($(this).data('min'))

		if (price < min) {
			price = min
		}

		$(this).val(price.toFixed(2))

		update_price($(this))
	})
})
