import {combineReducers, createStore, applyMiddleware} from "redux";
import thunk from "redux-thunk";


// importing stores
import PostsStore from "./Posts";
import CommentStore from "./Comment";

const reducers = combineReducers({
	PostsStore: PostsStore,
	CommentStore: CommentStore,
});

export default createStore(
	reducers,
	applyMiddleware(thunk),
);