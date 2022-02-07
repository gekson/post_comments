import {Grid, Table, TableBody, TableCell, TableHead, TableRow} from "@mui/material";
import HeaderMenu from "../components/HeaderMenu";
import {connect, useDispatch} from "react-redux";
import React, {useCallback, useEffect, useState} from "react";
import {create, erase, findPostWithRelationship, get, update} from "../actions/Comment";
import DeleteIcon from "@material-ui/icons/Delete";
import Button from "@mui/material/Button";
import AddNewCommentForm from "../components/Comment/AddNewCommentForm";
import EditIcon from "@material-ui/icons/Edit";
import {GET_POST} from "../actions/Comment/types";

const Comment = (props) => {
	const {
			// get = () => {
			// },
			findPostWithRelationship = () => {
			},
			erase = (id) => {
			},
			create = (data = {}) => {
			},
			update = (data = {}) => {
			},
			comments = [],
			comments_errors = {},
			post
		} = props,
		[
			addFormOpen = false,
			handleFormOpen = () => {
			}
		] = useState(false),
		[
			editComment,
			handleEditComment
		] = useState(null),
		dispatch = useDispatch(),
		clearOnAdd = useCallback(() => dispatch({
			type: GET_POST,
			comments_errors: [],
		}),
			[dispatch]
		)
	const search = props.location.search;
	const params = new URLSearchParams(search);

	let [post_id, handlePostIdChange] = useState(params.get('post_id'));

	let [description, handleDescriptionChange] = useState("");
	console.log(post_id, 'POSTID')

	useEffect(() => {
		if (!addFormOpen) {
			// get();
			findPostWithRelationship(post_id);
		}
		// get();
		findPostWithRelationship(post_id);
	}, [
		// get,
		findPostWithRelationship,
		addFormOpen
	]);

	return (
		<Grid
			xs={12}
			sm={12}
			lg={12}
			md={12}
			item
			container
		>
			<HeaderMenu history={props.history}/>

			<Grid
				xs={12}
				sm={12}
				lg={12}
				md={12}
				item
				container
			>
				<Button
					variant={"outlined"}
					onClick={() => {
						handleDescriptionChange("");
						handleEditComment(null);
						handleFormOpen(!addFormOpen);
						clearOnAdd();
					}}
				>
					{
						addFormOpen ? "Close form" : "Add new comments"
					}
				</Button>
			</Grid>
			{
				addFormOpen ?
					<AddNewCommentForm
						create={create}
						update={update}
						comments_errors={comments_errors}
						editComment={editComment}
						description={description}
						handleDescriptionChange={handleDescriptionChange}
						post_id={post_id}
						handleFormOpen={handleFormOpen}
					/>
					:
					<Table>
						<TableHead>
							<TableRow>
								<TableCell>
									ID
								</TableCell>
								<TableCell>
									Description
								</TableCell>
								<TableCell>
									CREATE DATE
								</TableCell>
								<TableCell>
									ACTIONS
								</TableCell>
							</TableRow>
						</TableHead>
						<TableBody>
							{comments.map(s =>
								<TableRow
									key={s.id}
								>
									<TableCell>
										{s.id}
									</TableCell>
									<TableCell>
										{s.description}
									</TableCell>
									<TableCell>
										{s.formatted_created_date}
									</TableCell>
									<TableCell>
										<Button
											aria-controls="basic-menu"
											aria-haspopup="true"
											onClick={() => erase(s.id, props.post_id)}
										>
											<DeleteIcon/>
										</Button>
										<Button
											aria-controls="basic-menu"
											aria-haspopup="true"
											onClick={() => {
												handleDescriptionChange(s.description);
												handleEditComment(s);
												handleFormOpen(true);
											}}
										>
											<EditIcon />
										</Button>
									</TableCell>
								</TableRow>
							)}
						</TableBody>
					</Table>
			}
		</Grid>
	);
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
	// get: () => dispatch(get()),
	erase: (id, post_id) => dispatch(erase(id, post_id)),
	create: (data = {}) => dispatch(create(data ?? false)),
	update: (id, data) => dispatch(update(id, data)),
	findPostWithRelationship: (id) => dispatch(findPostWithRelationship(id, ['comments', "user"])),
});

export default connect(mapStateToProps, mapDispatchToProps)(Comment);