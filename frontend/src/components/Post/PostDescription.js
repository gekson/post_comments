import {Grid, TextareaAutosize, Typography} from "@mui/material";
import * as React from "react";
import PropTypes from "prop-types";

const _render = (props = {
	description: "",
	descriptionFocus: false,
	setDescriptionFocus: () => {
	},
	setDescription: () => {},
}) => {

	if (props?.description === "") {
		return (
			<TextareaAutosize
				autoFocus
				className="task-description"
				onChange={(event) => {
					props?.setIsTyping(null);
					props?.setDescription(event.target.value);
				}}
				placeholder="Description"
				rows={1}
				value={props.description}
				onMouseLeave={() => {
					props.setDescriptionFocus(false);
					props?.setIsTyping(false);
				}}
			/>
		);
	}

	if (!props.descriptionFocus) {
		return (
			<TextareaAutosize
				autoFocus
				className="task-description"
				onChange={(event) => {
					props?.setIsTyping(null);
					props?.setDescription(event.target.value);
				}}
				placeholder="Description"
				rows={1}
				value={props.description}
				onMouseLeave={() => {
					props.setDescriptionFocus(false);
					props?.setIsTyping(false);
				}}
			/>
		);
	} else {
		return (
			<Typography
				onMouseEnter={() => {
					props.setDescriptionFocus(true);
					props?.setIsTyping(null);
				}}
			>
				{props.description}
			</Typography>
		);
	}
}

/**
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
const PostDescription = (props = {
	description: "",
	setDescription: () => {
	},
}): JSX.Element => (
	<Grid
		xs={6}
		sm={6}
		lg={6}
		md={6}
		item
	>
		{_render(props)}
	</Grid>
);

PostDescription.propTypes = {
	description: PropTypes.string.isRequired,
	setDescription: PropTypes.func.isRequired,
	descriptionFocus: PropTypes.bool.isRequired,
	setDescriptionFocus: PropTypes.func.isRequired,
};

export default PostDescription;