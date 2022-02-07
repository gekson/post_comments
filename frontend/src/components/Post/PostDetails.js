import * as React from 'react';
import Modal from '@mui/material/Modal';
import {Button, Grid} from "@mui/material";
import PostDetailsHeader from "./PostDetailsHeader";
import PostMainContent from "./PostMainContent";
import PostDetailFooter from "./PostDetailFooter";
import PropTypes from "prop-types";
import PostTitle from "./PostTitle";
import CloseIcon from "@mui/icons-material/Close";

/**
 * @param props
 * @returns {JSX.Element}
 * @private
 */
const _renderContent = (props = {
	post: {
		id: 0,
		title: "",
	}
}) => {

	return (

		<Grid
			xs={12}
			sm={12}
			lg={12}
			md={12}
			id={"post-details-body-wrapper"}
			item
			container
		>
			<PostDetailsHeader
				handleClose={props.handleChangeModal}
				postModalOpen={props.postModalOpen}
				find={props.find}
				post={props.post}
				update={props.update}
			/>
			<PostMainContent
				post={props.post}
				update={props.update}
			/>
			<PostDetailFooter
				post={props.post}
			/>
		</Grid>
	);
}


const PostDetails = (props = {
	postModalOpen: false,
	loading_posts: false,
	handleChangeModal: (open = false) => {},
	find: (id) => {},
	update: (id, body) => {},
	post: [],
}) => {

	return (
		<Grid
			xs={12}
			sm={12}
			md={12}
			lg={12}
			item
			container
		>
			<Modal
				open={props.postModalOpen}
				onClose={() => {
					props.handleChangeModal(!props.postModalOpen);
					props.find(false);
				}}
				className={"post-detail-modal-container"}
			>
				{_renderContent(props)}
			</Modal>
		</Grid>
	);
};

PostDetails.propTypes = {
	postModalOpen: PropTypes.bool,
    loading_posts: PropTypes.bool,
    handleChangeModal: PropTypes.func,
    find: PropTypes.func,
    update: PropTypes.func,
	post: PropTypes.shape({
		id: PropTypes.number.isRequired,
		title: PropTypes.string.isRequired
	}).isRequired,
};

export default PostDetails;
