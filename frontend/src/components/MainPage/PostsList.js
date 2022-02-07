import * as React from 'react';
import {Grid,} from "@mui/material";
import PostCard from "./PostCard";
import {comments} from "../../actions/Posts";

/**
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
const PostsList = (props = {
	posts: [],
	postId: null,
	postModalOpen: false,
	handleChangeModal: (open = false) => "",
	thumbsUp: (id) => {},
	thumbsDown: (id) => {},
	find: (id) => {},
	comments: (id) => {},
}): JSX.Element => {

	return (
		<Grid
			xs={12}
			sm={12}
			md={12}
			lg={12}
			id={"posts-list-status"}
			item
			container
		>
			{
				props?.posts?.map(p => (
					<PostCard
						p={p}
						component={props.component}
						postId={props.postId}
						history={props.history}
						thumbsUp={props.thumbsUp}
						thumbsDown={props.thumbsDown}
						find={props.find}
						comments={props.comments}
					/>
				))
			}
		</Grid>
	);
}

export default React.memo(PostsList);