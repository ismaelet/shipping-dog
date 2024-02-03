document.addEventListener('DOMContentLoaded', function () {
	document.querySelectorAll('input[type="date"]').forEach((element) => {
		element.addEventListener('focus', function () {
			this.showPicker()
		})
	})
})
