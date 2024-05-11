$(document).ready(function () {
	change($('#pan').data('stage'))

	$('#upload-box').click(function () {
		$('.file[name=file]').click()
	})

	$('#prev-over').click(function () {
		$('.file[name=file]').click()
	})

	function get_path() {
		var input = $('.file')[0]

		if (input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader()

				$('#preview-box').removeClass('hide')

				reader.onload = function (e) {
					$('#preview').attr('src', e.target.result)
				}

				reader.readAsDataURL(input.files[0])
			}
		}
	}

	$('.file[name=file]').change(function () {
		const acceptedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'png', 'webp']

		const extension = $(this).val().split('.').pop().toLowerCase()

		if (acceptedExtensions.includes(extension)) {
			change(2)
		} else {
			$('p.error').show()
			$('.wrong-extension').html(extension.toUpperCase())
		}
	})

	function change(stage) {
		if (stage == 1) {
			$('#setup-sec').show()
			$('#data-form').hide()
		} else if (stage == 2) {
			$('#setup-sec').hide()
			$('#data-form').show()
		}

		get_path()
	}
})
