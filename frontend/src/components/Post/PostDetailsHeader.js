import * as React from "react";
import {useEffect} from "react";
import CloseIcon from '@mui/icons-material/Close';
import {Button, Grid} from "@mui/material";
import PostTitle from "./PostTitle";
import PropTypes from "prop-types";


/**
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
const PostDetailsHeader = (props = {
	handleClose: () => {
	},
	find: (id) => {
	},
	update: (id, body) => {
	},
	post: {
		title: "",
		description: "",
	},
}) => {
	const {
		update = () => {
		},
		post = {
			title: "",
			description: "",
			id: 0,
		}
	} = props;
	const [title, setPostTitle] = React.useState(post?.title ?? "Type your post title");
	const [postTitleFocus, setPostTitleFocus] = React.useState(false);

	useEffect(() => {
		if (!postTitleFocus && post.title !== title) {
			update(post.id, {
				title
			});
		}
	}, [
		title,
		postTitleFocus,
		update,
		post.title,
		post.id,
	]);

	return (
		<Grid
			xs={12}
			sm={12}
			md={12}
			lg={12}
			alignContent={"center"}
			alignItems={"center"}
			direction={'column'}
			classtitle={"post-details-header-container"}
			item
			container
		>
			<Grid
				xs={12}
				sm={12}
				md={12}
				lg={12}
				direction={'row'}
				alignItems={"center"}
				alignContent={"center"}
				spacing={2}
				item
				container
			>
				<PostTitle
					title={title}
					postTitleFocus={postTitleFocus}
					setPostTitleFocus={setPostTitleFocus}
					setPostTitle={setPostTitle}
				/>
				<Grid
					xs={1}
					sm={1}
					md={1}
					lg={1}
					alignContent={"flex-end"}
					alignItems={"flex-end"}
					direction={'column'}
					item
					container
				>
					<Button
						variant={"outlined"}
						color={"primary"}
						onClick={() => {
							props.handleClose(props.postModalOpen);
							props.find(false);
						}}
					>
						<CloseIcon/>
					</Button>
				</Grid>
			</Grid>
		</Grid>
	);
};

PostDetailsHeader.propTypes = {
	handleClose: PropTypes.func.isRequired,
	find: PropTypes.func.isRequired,
	update: PropTypes.func.isRequired,
	post: PropTypes.shape({
		id: PropTypes.number.isRequired,
		title: PropTypes.string.isRequired
	}).isRequired,
};

export default PostDetailsHeader;