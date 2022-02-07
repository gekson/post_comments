
import React, {useEffect} from "react";
import PropTypes from "prop-types";
import {isEmptyObject} from "../../utils/Helpers";
import {Button, FormControl, Grid, OutlinedInput} from "@mui/material";


const showErrors = (comments_errors = {}) => {
	if (!isEmptyObject(comments_errors)) {
		const view = [];
		for (var key in comments_errors) {
			if (comments_errors[key].length > 0) {
				comments_errors[key].forEach(s =>
					view.push(
						<li key={s}>
							{s}
						</li>
					)
				)
			}

			return (
				<FormControl
					fullWidth
				>
					<ul>
						{view}
					</ul>
				</FormControl>
			);
		}
	}

	return []
}

const actionButtons = (editComment = null, description, props) => {

	const post_id = props.post_id;
	if (editComment) {
		return (
			<Button
				variant={"outlined"}
				onClick={() => props.update(editComment.id, {
					description,
					post_id,
				})}
				disabled={description === ""}
			>
				EDIT
			</Button>
		);
	}

	return (
		<Button
			variant={"outlined"}
			onClick={() => props.create({
				description,
				post_id,
			})}
			disabled={description === ""}
		>
			Add
		</Button>
	);
};

const AddNewCommentForm = (props = {
	create: () => {
	},
	update: () => {
	},
	handleDescriptionChange: () => {
	},
	handleFormOpen: () => {},
	comments_errors: {},
	editComment: null,
	description: "",
	post_id:0
}) => {

	useEffect(() => false, [
		props.comments_errors,
	]);

	return (
		<Grid
			xs={12}
			lg={12}
			sm={12}
			md={12}
			item
		>
			<FormControl
				variant={"outlined"}
				fullWidth
			>
				<OutlinedInput
					onChange={(event) => {
						props.handleDescriptionChange(event.target.value);
					}}
					placeholder={"Comment description"}
					label={"Comment description"}
					description={"description"}
					id={"description"}
					defaultValue={props.description}
					fullWidth
				/>
			</FormControl>
			{showErrors(props.comments_errors)}
			<FormControl
				variant={"outlined"}
			>
				{actionButtons(props.editComment, props.description, props)}
			</FormControl>
		</Grid>
	);
}

AddNewCommentForm.propTypes = {
	create: PropTypes.func.isRequired,
	comments_errors: PropTypes.any,
}

export default AddNewCommentForm;