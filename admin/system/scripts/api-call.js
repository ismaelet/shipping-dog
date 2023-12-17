async function API(parcialEndpoint, method, content = {}, debug = false) {
	let responseData

	const root = document.querySelector('html').getAttribute('data-client-root')

	let options = {}

	// generate endpoint
	let endpoint = root + '/api/' + parcialEndpoint

	// process the request content
	if (method == 'GET') {
		if (content.set) {
			let setRequest = []

			for (const [key, value] of Object.entries(content.set)) {
				setRequest.push(key + ':' + value)
			}

			content.set = setRequest
		}

		endpoint += '?' + new URLSearchParams(content)
	} else {
		options = {
			method: method,
			body: JSON.stringify(content),
		}
	}

	// send the request
	const response = await fetch(endpoint, options)

	// debugger
	if (debug) {
		const debugResponse = response.clone()
		console.log(await debugResponse.text())
	}

	// parse the response
	responseData = await response.text()

	try {
		responseData = JSON.parse(responseData)

		if (responseData.error) {
			console.log('Error: ' + responseData.error)
		}
	} catch (err) {
		console.log(responseData)

		responseData = null
	}

	// return the response
	return responseData
}

async function AJAX(parcialEndpoint, parameters = {}, contentType = 'json', debug = false) {
	let responseData

	// generate endpoint
	const root = document.querySelector('html').getAttribute('data-client-root')

	let endpoint = parcialEndpoint[0] == '/' ? root + parcialEndpoint : root + '/ajax/' + parcialEndpoint

	if (parameters) endpoint += '?' + new URLSearchParams(parameters)

	// send the request
	const response = await fetch(endpoint)

	// debugger
	if (debug) {
		const debugResponse = response.clone()
		console.log(await debugResponse.text())
	}

	// parse the response
	responseData = await response.text()

	switch (contentType) {
		case 'json':
			try {
				responseData = JSON.parse(responseData)
			} catch (err) {
				console.log('Error: ' + responseData)
				responseData = null
			}
			break
		case 'text':
			responseData = responseData
			break
		default:
			responseData = null
	}

	// return the response
	return responseData
}
