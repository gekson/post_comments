import {FIND_POSTS, GET_POSTS} from "../../actions/Posts/types";

const initial_state = {
	posts: [],
	loading_posts: false,
	loading_post: false,
	post: {
		id: 0,
		title: "",
		description: ""
	},
	posts_errors: {},
}

const PostsStore = (state = initial_state, action) => {

	if (action.type === GET_POSTS) {
		return {
			...state,
			posts: action.posts,
			loading_posts: action?.loading_posts,
			posts_errors: action?.posts_errors ?? {},
		}
	}
	if (action.type === GET_POSTS) {
		return {
			...state,
			posts: action.posts,
			loading_posts: action?.loading_posts,
			posts_errors: action?.posts_errors ?? {},
		}
	}

	if (action.type === FIND_POSTS) {
		return {
			...state,
			loading_posts: action?.loading_posts,
			posts_errors: action?.posts_errors ?? {},
			post: action?.post ?? {},
		}
	}

	return state;
}

export default PostsStore;