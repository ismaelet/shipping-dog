document.addEventListener('DOMContentLoaded', function () {
	const previewImage = document.querySelector('.preview-image')

	document.querySelectorAll('[name="siz_x_def"]').forEach((element) => {
		element.addEventListener('input', () => {
			const value = element.value
			const inputBlock = element.closest('.range-input')

			previewImage.style.setProperty('--default-x-size', value)
			inputBlock.querySelector('span').innerHTML = value
		})
	})

	document.querySelectorAll('[name="siz_x_max"]').forEach((element) => {
		element.addEventListener('input', () => {
			const value = element.value
			const inputBlock = element.closest('.range-input')

			previewImage.style.setProperty('--max-x-size', value)
			inputBlock.querySelector('span').innerHTML = value
		})
	})

	document.querySelectorAll('[name="siz_y_max"]').forEach((element) => {
		element.addEventListener('input', () => {
			const value = element.value
			const inputBlock = element.closest('.range-input')

			previewImage.style.setProperty('--max-y-size', value)
			inputBlock.querySelector('span').innerHTML = value
		})
	})

	document.querySelectorAll('[name="pos_x_def"]').forEach((element) => {
		element.addEventListener('input', () => {
			const value = element.value
			const inputBlock = element.closest('.range-input')

			previewImage.style.setProperty('--default-x-position', value)
			inputBlock.querySelector('span').innerHTML = value
		})
	})

	document.querySelectorAll('[name="pos_x_min"]').forEach((element) => {
		element.addEventListener('input', () => {
			const value = element.value
			const inputBlock = element.closest('.range-input')

			previewImage.style.setProperty('--starting-x-position', value)
			inputBlock.querySelector('span').innerHTML = value
		})
	})

	document.querySelectorAll('[name="pos_y_def"]').forEach((element) => {
		element.addEventListener('input', () => {
			const value = element.value
			const inputBlock = element.closest('.range-input')

			previewImage.style.setProperty('--default-y-position', value)
			inputBlock.querySelector('span').innerHTML = value
		})
	})

	document.querySelectorAll('[name="pos_y_min"]').forEach((element) => {
		element.addEventListener('input', () => {
			const value = element.value
			const inputBlock = element.closest('.range-input')

			previewImage.style.setProperty('--starting-y-position', value)
			inputBlock.querySelector('span').innerHTML = value
		})
	})
})
