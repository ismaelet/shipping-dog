document.addEventListener('DOMContentLoaded', function () {
	document.querySelector('.js-copy-orders').addEventListener('click', async function () {
		// Get the HTML content of the entire document
		const text = document.querySelector('.orders').outerHTML

		copyRichTextToClipboard(text)
	})

	async function copyRichTextToClipboard(html) {
		try {
			await navigator.clipboard.write([
				new ClipboardItem({
					'text/html': new Blob([html], { type: 'text/html' }),
				}),
			])
			console.log('Rich text copied to clipboard')
			return true
		} catch (err) {
			console.error('Failed to copy rich text: ', err)
			return false
		}
	}
})
