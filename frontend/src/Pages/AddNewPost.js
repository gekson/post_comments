import React from "react";
import HeaderMenu from "../components/HeaderMenu";
import {Button, FormControl, Grid, OutlinedInput} from "@mui/material";
import {connect} from "react-redux";
import {create, find} from "../actions/Posts";

class AddNewPost extends React.Component {
	constructor(props) {
		super(props);
		this.state = {
			title: "",
			description: "",
			action: () => {
			},
		}
	}

	componentDidUpdate(prevProps: Readonly<P>, prevState: Readonly<S>, snapshot: SS) {
		if (this.props !== prevProps) {

		}
	}

	render() {
		const
			{
				title = "",
				description = "",
			} = this.state;

		return (
			<Grid
				xs={12}
				sm={12}
				lg={12}
				md={12}
				item
				container
			>
				<HeaderMenu history={this.props.history}/>
				<FormControl fullWidth>
					<OutlinedInput
						placeholder={"Post title"}
						id={"title"}
						name={"title"}
						value={title}
						onChange={(event) =>
							this.setState({
								title: event.target.value,
							})
						}
						fullWidth
					/>
				</FormControl>
				<FormControl fullWidth>
					<OutlinedInput
						placeholder={"Description"}
						id={"description"}
						name={"description"}
						value={description}
						onChange={(event) =>
							this.setState({
								description: event.target.value,
							})
						}
						fullWidth
					/>
				</FormControl>

				<FormControl fullWidth>
					<Button
						variant={"outlined"}
						onClick={() => this.props.create({
							title,
							description,
						})}
						disabled={title === "" || description === ""}
					>
						ADD NEW POST
					</Button>
				</FormControl>
			</Grid>
		);
	}
}

const mapStateToProps = state => {
	return {
		...state.PostStore,
	};
}

const mapDispatchToProps = () => dispatch => ({
	create: (data) => dispatch(create(data)),
	find: (id) => dispatch(find(id)),
});

export default connect(mapStateToProps, mapDispatchToProps)(AddNewPost);