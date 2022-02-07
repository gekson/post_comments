import Request from "../../utils/Request";
import {CREATE_POSTS, DELETE_POST, FIND_POSTS, GET_POSTS, THUMBSDOWN_POST, THUMBSUP_POST, UPDATE_POST} from "./types";
import {filterError, getUserToken} from "../../utils/Helpers";


export const get = (relationships = []) => dispatch => {
	dispatch({
		type: GET_POSTS,
		loading_posts: true,
	});

	let url = "/post";

	if (relationships.length > 0) {
		relationships = Request.parseParams(relationships);
		url = `/post?${relationships}`;
	}

	Request.get(url)
		.then(response => {

			dispatch({
				type: GET_POSTS,
				loading_posts: false,
				posts: response.data.data,
			});

		})
		.catch(error => {
			dispatch({
				type: GET_POSTS,
				loading_posts: false
			});
			filterError(error);
		});
}

export const update = (id, body) => dispatch => {
	if (id && body) {
		dispatch({
			type: UPDATE_POST,
			loading_posts: true,
		});

		Request.put(`/post/${id}`, body)
			.then(() => {
				dispatch(get());
				dispatch(find(id, [
					'status',
				]));
				dispatch({
					type: UPDATE_POST,
					loading_posts: false,
				});
			})
			.catch(error => filterError(error));

		dispatch({
			type: UPDATE_POST,
			loading_posts: false,
		});
	}
}

export const erase = (id) => dispatch => {
	if (id) {
		dispatch({
			type: DELETE_POST,
			loading_posts: true,
		});

		Request.delete(`/post/${id}`)
			.then(() => {
				dispatch(get());
				dispatch({
					type: UPDATE_POST,
					loading_posts: false,
				});
			})
			.catch(error => filterError(error));
	}
};

export const create = (data = {}) => dispatch => {
	if (data) {
		dispatch({
			type: CREATE_POSTS,
			loading_posts: true,
			posts_errors: {},
		});
		Request.post("/post", {
				posts: {
					...data,
				}
			}, {headers: {Authorization: `Bearer ${getUserToken()}`}})
			.then(() => dispatch(get()))
			.catch(error => {
				dispatch({
					type: CREATE_POSTS,
					loading_posts: false,
					posts_errors: error?.response?.data ?? {},
				});
				filterError(error);
			});
	}
}

export const find = (id, relationships = []) => dispatch => {
	dispatch({
		type: FIND_POSTS,
		loading_post: true,
		posts_errors: {},
		posts: {},
		post: {
			id: 0,
			title: "",
			description: ""
		}
	});
	if (id) {
		let url = `/post/find/${id}`;

		if (relationships.length > 0) {
			relationships = Request.parseParams(relationships);
			url += `?${relationships}`;
		}

		Request.get(url)
			.then((response) => {
				dispatch({
					type: FIND_POSTS,
					post: response?.data.data,
					loading_post: false,
				});
			})
			.catch(error => {
				filterError(error);
				dispatch({
					type: FIND_POSTS,
					loading_post: false,
					posts_errors: error?.response?.data ?? {},
				});
			});
	} else {
		dispatch(get([
			"user",
			"comments"
		]));
	}
};

export const thumbsUp = (id) => dispatch => {
	if (id) {
		dispatch({
			type: THUMBSUP_POST,
			loading_posts: true,
			posts_errors: {},
		});
		Request.get(`/post/like/${id}`)
			.then(() => dispatch(get()))
			.catch(error => {
				dispatch({
					type: THUMBSUP_POST,
					loading_posts: false,
					posts_errors: error?.response?.data ?? {},
				});
				filterError(error);
			});
	}
};

export const thumbsDown = (id) => dispatch => {
	if (id) {
		dispatch({
			type: THUMBSDOWN_POST,
			loading_posts: true,
			posts_errors: {},
		});
		Request.get(`/post/dislike/${id}`)
			.then(() => dispatch(get()))
			.catch(error => {
				dispatch({
					type: THUMBSDOWN_POST,
					loading_posts: false,
					posts_errors: error?.response?.data ?? {},
				});
				filterError(error);
			});
	}
};

export const comments = (id) => dispatch => {
	if (id) {
		// dispatch(findWithRelationship(id, ["user", "comments"]));
		window.location.href = "/comment?post_id="+id;
	}
};