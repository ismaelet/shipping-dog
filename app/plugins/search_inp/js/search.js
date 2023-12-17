$(document).ready(function () {
	$('.ajax-search-txt').keyup(function () {
		//setup
		var self = $(this)
		var parent = self.parents('.ajax-search')
		var url = parent.data('url')
		var filter = parent.data('filter')

		$.ajax({
			type: 'POST',

			url: '/app/functions/search/' + url + '.php',

			data: { search: self.val(), filter: filter },

			success: function (data) {
				if ($.trim(data).length) {
					parent.find('.ajax-search-ddown').show()
					parent.find('.ajax-search-box').html(data)
				} else {
					parent.find('.ajax-search-ddown').hide()
					parent.find('.ajax-search-box').html('')
				}
			},
		})
	})

	$('.ajax-search').on('click', '.ajax-search-item', function () {
		var self = $(this)
		var parent = self.parents('.ajax-search')

		parent.find('.ajax-search-id').val(self.data('id'))
		parent.find('.ajax-search-txt').val(self.data('txt'))

		parent.find('.ajax-search-ddown').hide()
	})

	$('.inp, .pbtn').focus(function () {
		$('.ajax-search').find('.ajax-search-ddown').hide()
		$('.ajax-search').find('.ajax-search-box').html('')
	})
})
