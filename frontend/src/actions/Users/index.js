import Request from "../../utils/Request";
import {filterError, storeUserToken} from "../../utils/Helpers";

export const login = (data = {}) => dispatch => {
	if (data) {
		Request.post("/login", data)
			.then(response => {
				storeUserToken(response.data.data.token);
				window.location.href = "main-page";
			})
			.catch(error => filterError(error));
	}
}

export const logout = () => dispatch => (
	Request.post("/users/logout")
		.then(() => window.location.href = "login")
		.catch(error => filterError(error))
);

/**
 * @param data
 * @returns {(function(*): void)|*}
 */
export const create = (data) => dispatch => {
	if (data) {
		Request.post("/register", {
			users: {
					...data,
				}
			})
			.then((response) => {
				if (response.data) {
					sessionStorage.clear();
					localStorage.clear();
					window.location.href = "login";
				}
			})
			.catch(error => filterError(error));
	}
}
