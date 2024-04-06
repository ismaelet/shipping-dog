function redirect(url) {
	const root = document.querySelector('html').getAttribute('data-client-root')

	window.location.href = root + url
}

function client_url(baseUrl) {
	const clientRoot = document.querySelector('html').getAttribute('data-client-root')
	const url = clientRoot + baseUrl

	return url
}
