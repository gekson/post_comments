/**
 * @param object
 * @returns {boolean}
 */
export const isEmptyObject = (object) => {
	for (var key in object) {
		if (object.hasOwnProperty(key)) {
			return false;
		}
	}

	return true;
}

/**
 * @param token
 */
export const storeUserToken = (token) => {
	if (token) {
		sessionStorage.clear();
		localStorage.clear();

		sessionStorage.setItem("authToken", token);
		localStorage.setItem("authToken", token);
	}
}

/**
 * @returns {string|null}
 */
export const getUserToken = (): string =>
	sessionStorage.getItem("authToken") ?? localStorage.getItem("authToken") ?? "";


/**
 * @param error
 */
export const filterError = (error) => {
	if (error) {
		let status = 0;
		if (error?.response) {
			status = error?.response?.status ?? 0;
		}

		console.error(error);

		switch (status) {
			case 401:
				window.location.href = "/login";
			break;

			default:
				return;
		}
	}
}