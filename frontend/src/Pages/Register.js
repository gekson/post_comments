import {useState} from "react";
import {Button, Grid, TextField} from "@mui/material";
import {connect} from "react-redux";
import {create} from "../actions/Users";

/**
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
const LoginPage = (props = {
	login: () => {
	}
}): JSX.Element => {
	const [email, handleEmailChange] = useState("");
	const [password, handlePasswordChange] = useState("");
	const [name, handleChangeName] = useState("");
	const [password_confirmation, handlePasswordConfirmationChange] = useState("");

	return (
		<Grid
			xs={12}
			sm={12}
			lg={12}
			md={12}
			item
			container
		>
			<Grid
				xs={12}
				sm={12}
				lg={12}
				md={12}
				item
			>
				<TextField
					placeholder={"Type your name"}
					variant={"outlined"}
					value={name}
					onChange={event => handleChangeName(event.target.value)}
					fullWidth
				/>
			</Grid>
			<Grid
				xs={12}
				sm={12}
				lg={12}
				md={12}
				item
			>
				<TextField
					type={"email"}
					placeholder={"Type your email"}
					variant={"outlined"}
					value={email}
					onChange={event => handleEmailChange(event.target.value)}
					fullWidth
				/>
			</Grid>

			<Grid
				xs={12}
				sm={12}
				lg={12}
				md={12}
				item
			>
				<TextField
					placeholder={"Type your password"}
					type={"password"}
					variant={"outlined"}
					value={password}
					onChange={event => handlePasswordChange(event.target.value)}
					fullWidth
				/>
			</Grid>

			<Grid
				xs={12}
				sm={12}
				lg={12}
				md={12}
				item
			>
				<TextField
					placeholder={"Type your password confirmation"}
					type={"password"}
					variant={"outlined"}
					value={password_confirmation}
					onChange={event => handlePasswordConfirmationChange(event.target.value)}
					fullWidth
				/>
			</Grid>

			<Grid
				xs={12}
				sm={12}
				lg={12}
				md={12}
				item
			>
				<Button
					variant={"outlined"}
					disabled={!email || !password || !name}
					onClick={() => props.create({
						email,
						password,
						name,
						password_confirmation
					})}
					fullWidth
				>
					REGISTER
				</Button>
			</Grid>

			<Grid
				xs={12}
				sm={12}
				lg={12}
				md={12}
				item
			>
				<Button
					variant={"outlined"}
					onClick={() => props.history.push("/login")}
					fullWidth
				>
					LOGIN
				</Button>
			</Grid>
		</Grid>
	);
}

const mapDispatchToProps = dispatch => ({
	create: (data = {}) => dispatch(create(data))
});

export default connect(false, mapDispatchToProps)(LoginPage);