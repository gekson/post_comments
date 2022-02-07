import {CREATE_COMMENT, GET_POST, UPDATE_COMMENT, FIND_POST} from "../../actions/Comment/types";

const initial_state = {
	comments: [],
	loading_comments: false,
	comments_errors: {},
	post: {
		id: 0,
		title: "",
		description: ""
	},
}

const CommentStore = (state = initial_state, action) => {
	const {
		loading_comments = false
	} = action;

	if (action.type === GET_POST) {
		return {
			...state,
			loading_comments,
			comments: action?.comments ?? [],
			comments_errors: action?.stack ?? {},
		}
	}

	if (action.type === CREATE_COMMENT) {
		return {
			...state,
			loading_comments,
			comments_errors: action?.comments_errors ?? {},
		}
	}

	if (action.type === UPDATE_COMMENT) {
		return {
			...state,
			loading_comments,
			comments_errors: action?.comments_errors ?? {},
		}
	}

	if (action.type === FIND_POST) {
		return {
			...state,
			loading_comments,
			comments: action?.comments ?? [],
			comments_errors: action?.stack ?? {},
			post: action?.post ?? {},
		}
	}

	return state;
}

export default CommentStore;