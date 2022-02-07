import React from "react";
import {connect} from "react-redux";
import {comments, erase, find, get, thumbsDown, thumbsUp, update} from "../actions/Posts";
import {get as getComment} from '../actions/Comment';
import HeaderMenu from "../components/HeaderMenu";
import {Button, Grid,} from "@mui/material";
import PostsList from "../components/MainPage/PostsList";
import {HTML5Backend} from "react-dnd-html5-backend";
import {DndProvider} from "react-dnd";
import PostDetails from "../components/Post/PostDetails";


class MainPage extends React.Component {
	constructor(props) {
		super(props);
		this.state = {
			changeStatusOptionsOpen: false,
			postId: null,
			postModalOpen: false,
		};
		this.props.get();
	}

	componentDidUpdate(prevProps: Readonly<P>, prevState: Readonly<S>, snapshot: SS) {
		if (this.props !== prevProps) {
			const {
					post = {}
				} = this.props;

			if (post?.id) {
				this.setState({
					postModalOpen: true,
				});
			} else {
				this.setState({
					postModalOpen: false,
				});
			}
		}
	}

	handleChangeModal = (open = false) => this.setState({
		postModalOpen: !open,
	});

	render() {
		const {
				posts = [],
				post = {
					id: 0,
					title: "",
				},
				loading_posts = false,
				loading_post = false,
			} = this.props,
			{
				postId = null,
				postModalOpen = false,
			} = this.state;

		return (
			<Grid
				id={"main-page-container"}
				xs={12}
				sm={12}
				lg={12}
				md={12}
				item
				container
			>
				<HeaderMenu history={this.props.history}/>
				<Grid
					id={"main-page-buttons-container"}
					xs={12}
					sm={12}
					lg={12}
					md={12}
					item
					container
				>
					<Button
						variant={"outlined"}
						onClick={() => this.props.history.push("/posts/add")}
					>
						ADD NEW POST
					</Button>
				</Grid>


				<DndProvider backend={HTML5Backend}>
					<PostsList
						posts={posts ?? []}
						postId={postId}
						component={this}
						update={this.props.update}
						erase={this.props.erase}
						handleChangeModal={this.handleChangeModal}
						postModalOpen={postModalOpen}
						find={this.props.find}
						history={this.props.history}
						thumbsUp={this.props.thumbsUp}
						thumbsDown={this.props.thumbsDown}
						comments={this.props.comments}
					/>
				</DndProvider>
				{
					!loading_post && (
						<PostDetails
							handleChangeModal={this.handleChangeModal}
							postModalOpen={postModalOpen}
							find={this.props.find}
							post={post}
							update={this.props.update}
							loading_posts={loading_posts}
						/>
					)
				}
			</Grid>
		);
	}
}

const mapStateToProps = state => {
	const {
		CommentStore,
		PostsStore,
	} = state;

	return {
		...CommentStore,
		...PostsStore,
	}
}

const mapDispatchToProps = dispatch => ({
	get: () => dispatch(get([
		"post",
	])),
	getComment: dispatch(getComment()),
	update: (id, body) => dispatch(update(id, body)),
	erase: (id) => dispatch(erase(id)),
	find: (id) => dispatch(find(id, ['comments', "user"])),
	thumbsUp: (id) => dispatch(thumbsUp(id)),
	thumbsDown: (id) => dispatch(thumbsDown(id)),
	comments: (id) => dispatch(comments(id)),
});

export default connect(mapStateToProps, mapDispatchToProps)(MainPage);
