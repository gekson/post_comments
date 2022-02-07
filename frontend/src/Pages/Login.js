import {useState} from "react";
import {Button, Grid, TextField} from "@mui/material";
import {connect} from "react-redux";
import {login} from "../actions/Users";


const LoginPage = (props = {
	login: () => {
	},
	history: {
		push: () => {},
	}
}) => {
	const [email, handleEmailChange] = useState("");
	const [password, handlePasswordChange] = useState("");

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
				<Button
					variant={"outlined"}
					disabled={!email || !password}
					onClick={() => props.login({
						email,
						password,
					})}
					fullWidth
				>
					Login
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
					onClick={() => props.history.push("/register")}
					fullWidth
				>
					REGISTER
				</Button>
			</Grid>
		</Grid>
	);
}

const mapDispatchToProps = dispatch => ({
	login: (data = {}) => dispatch(login(data))
});

export default connect(false, mapDispatchToProps)(LoginPage);