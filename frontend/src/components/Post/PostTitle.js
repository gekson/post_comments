import * as React from "react";

import {Grid, TextField} from "@mui/material";
import Typography from "@mui/material/Typography";
import PropTypes from "prop-types";


/**
 * @param title
 * @param postTitleFocus
 * @param setPostTitleFocus
 * @param setPostTitle
 * @returns {JSX.Element}
 * @private
 */
const _handleChangePostTitleContainer = (title, postTitleFocus, setPostTitleFocus, setPostTitle) => {
	if (postTitleFocus) {
		return (
			<TextField
				value={title}
				onMouseLeave={() => setPostTitleFocus(false)}
				onChange={(e) => {
					setPostTitle(e.target.value);
				}}
				fullWidth
			/>
		);
	}

	return (
		<Typography
			classtitle={"post-title"}
			onMouseEnter={() => setPostTitleFocus(true)}
		>
			{title}
		</Typography>
	);
}

/**
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
const PostTitle = (
	props = {
		title: "",
		postTitleFocus: false,
		setPostTitleFocus: () => {},
		setPostTitle: () => {},
	}
): JSX.Element => (

	<Grid
		xs={5}
		sm={5}
		md={5}
		lg={5}
		alignContent={"flex-start"}
		alignItems={"flex-start"}
		direction={'column'}
		item
		container
	>
		{_handleChangePostTitleContainer(
			props?.title ?? "",
			props?.postTitleFocus ?? false,
			props?.setPostTitleFocus,
			props?.setPostTitle,
		)}
	</Grid>
);

PostTitle.propTypes = {
	title: PropTypes.string.isRequired,
    postTitleFocus: PropTypes.bool.isRequired,
    setPostTitleFocus: PropTypes.func.isRequired,
    setPostTitle: PropTypes.func.isRequired,
};

export default PostTitle;