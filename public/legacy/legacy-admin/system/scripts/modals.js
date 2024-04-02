$(document).ready(function () {
	const openModal = $('[data-function="open-modal"]')
	const closeModal = $('.modal, [data-function="close-modal"]')

	openModal.click((e) => {
		const target = e.target.getAttribute('data-target')
		const modal = document.querySelector('#' + target)

		document.querySelector('body').classList.add('fixed')

		modal.classList.add('open')
	})

	closeModal.click((e) => {
		if (e.target == e.currentTarget) {
			const modal = e.target.closest('.modal')

			document.querySelector('body').classList.remove('fixed')

			modal.classList.remove('open')
		}
	})
})
