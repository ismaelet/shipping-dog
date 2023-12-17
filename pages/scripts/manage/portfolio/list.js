$(document).ready(function () {
	$('.js-sortable').sortable({
		update: function () {
			$('.js-order').each(function () {
				const order = $('.js-order').index(this) + 1

				$(this).val(order)

				$('#sort-btn').removeClass('hidden')
			})
		},
	})
})
