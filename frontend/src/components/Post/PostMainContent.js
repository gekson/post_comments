import {Grid } from "@mui/material";
import * as React from "react";
import PostDescription from "./PostDescription";
import {useEffect} from "react";

/**
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
const PostMainContent = (props = {
	post: {
		description: "",
		id: null,
	},
	update: (id, body) => {
	},
}): JSX.Element => {
	const [description, setDescription] = React.useState(props?.post?.description ?? "");
	const [descriptionFocus, setDescriptionFocus] = React.useState(false);
	const [isTyping, setIsTyping] = React.useState(null);
	const {
		update = () => {
		},
		post = {
			description: "",
		}
	} = props;

	console.log(isTyping);
	console.log(descriptionFocus);

	useEffect(() => {
		if (!descriptionFocus) {
			if (isTyping === false) {
				if (description !== post.description) {
					update(post.id, {description});
				}
			}
		}
	}, [
		descriptionFocus,
		description,
		post.description,
		update,
		isTyping,
	]);

	return (
		<Grid
			xs={12}
			sm={12}
			lg={12}
			md={12}
			spacing={2}
			item
			container
		>
			<PostDescription
				description={description}
				setDescription={setDescription}
				descriptionFocus={descriptionFocus}
				setDescriptionFocus={setDescriptionFocus}
				setIsTyping={setIsTyping}
				isTyping={isTyping}
			/>
		</Grid>
	);
}

export default PostMainContent;