import Request from "../../utils/Request";
import {CREATE_COMMENT, DELETE_COMMENT, FIND_POST, GET_POST, UPDATE_COMMENT} from "./types";
import {filterError, getUserToken} from "../../utils/Helpers";


export const get = () => dispatch => {
	dispatch({
		type: GET_POST,
		loading_comments: true,
		comments: [],
		comments_errors: {},
	});

	Request.get("/comment")
		.then(response => {

			dispatch({
				type: GET_POST,
				loading_comments: false,
				comments: response.data,
				comments_errors: {},
			});
		})
		.catch(error => {
			dispatch({
				type: GET_POST,
				comments: false,
				comments_errors: {},
			});
			filterError(error);
		});
}


export const erase = (id, post_id) => dispatch => {
	if (id) {
		dispatch({
			type: DELETE_COMMENT,
			loading_comments: true,
		});

		Request.delete(`/comment/${id}`, {headers: {Authorization: `Bearer ${getUserToken()}`}})
			.then(() => {
				dispatch(findPostWithRelationship(post_id));
				dispatch({
					type: DELETE_COMMENT,
					loading_comments: false,
					comments_errors: {},
				});
			})
			.catch(error => filterError(error));
	}
};

export const create = (data = {}) => dispatch => {
	if (data) {
		dispatch({
			type: CREATE_COMMENT,
			loading_comments: true,
			comments_errors: {},
		});
		Request.post("/comment", {
			comments: {
				...data,
			}
		}, {headers: {Authorization: `Bearer ${getUserToken()}`}})
			.then(() => dispatch(get()))
			.catch(error => {
				dispatch({
					type: CREATE_COMMENT,
					loading_comments: false,
					comments_errors: error?.response?.data ?? {},
				});
				filterError(error);
			});
	}
}

export const update = (id, data = {}) => dispatch => {
	if (id && data) {
		dispatch({
			type: UPDATE_COMMENT,
			loading_comments: true,
			comments_errors: {},
		});
		Request.put(`/comment/${id}`, {
			comments: {
				...data,
			}
		}, {headers: {Authorization: `Bearer ${getUserToken()}`}})
			.then(() => dispatch(get()))
			.catch(error => {
				dispatch({
					type: UPDATE_COMMENT,
					loading_comments: false,
					comments_errors: error?.response?.data ?? {},
				});
				filterError(error);
			});
	}
};

export const findPostWithRelationship = (id, relationships = []) => dispatch => {
	dispatch({
		type: FIND_POST,
		loading_comments: true,
		comments_errors: {},
		post: {
			id: 0,
			title: "",
			description: ""
		}
	});

	if (id) {
		let url = `/post/findWithRelationship/${id}`;

		if (relationships.length > 0) {
			relationships = Request.parseParams(relationships);
			url += `?${relationships}`;
		}

		Request.get(url)
			.then((response) => {
				dispatch({
					type: FIND_POST,
					post: response?.data.data,
					loading_comments: false,
					comments: response?.data.data.comments,
				});

			})
			.catch(error => {
				filterError(error);
				dispatch({
					type: FIND_POST,
					loading_comments: false,
					comments_errors: error?.response?.data ?? {},
				});
			});
	} else {
		dispatch(get([
			"user",
			"comments"
		]));
	}
};