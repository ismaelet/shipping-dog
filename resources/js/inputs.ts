/**
 * Switch the color scheme of the website
 */
export function advancedInputBehaviorSetup() {
	/*
	 * Checkbox that behaves like a radio button, but with the ability to uncheck
	 */
	const inputSelector = '.js-radio-checkbox'

	document.querySelectorAll(inputSelector).forEach((element) => {
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
}
