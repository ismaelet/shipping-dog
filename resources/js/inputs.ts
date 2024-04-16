const submitFormSelector = '.js-submit-form'
const radioCheckboxSelector = '.js-radio-checkbox'

/**
 * Switch the color scheme of the website
 */
export function advancedInputBehaviorSetup() {
	/*
	 * Checkbox that behaves like a radio button, but with the ability to uncheck
	 */

	document.querySelectorAll(radioCheckboxSelector).forEach((element) => {
		element.addEventListener('change', () => {
			const inputElement = element as HTMLInputElement

			if (inputElement.checked) {
				// Uncheck all other checkboxes with the same name
				document.querySelectorAll(`input[type="checkbox"][name="${inputElement.name}"]`).forEach((checkbox) => {
					const checkboxElement = checkbox as HTMLInputElement

					if (checkboxElement !== inputElement) {
						checkboxElement.checked = false
					}
				})
			}
		})
	})

	/*
	 * Element that submits the parent form when clicked
	 */
	document.querySelectorAll(submitFormSelector).forEach((element) => {
		element.addEventListener('click', () => {
			const parentForm = element.closest('form')
			const childForm = element.querySelector('form')

			if (parentForm) {
				return parentForm.submit()
			}

			console.log(childForm)
			if (childForm) {
				return childForm.submit()
			}
		})
	})
}
