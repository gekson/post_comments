
import PropTypes from 'prop-types';
import {Button, Grid} from "@mui/material";
import {connect} from "react-redux";
import {logout} from "../actions/Users";


/**
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
const HeaderMenu = (props = {
	history: {
		push: () => {},
	}
}) => (
	<Grid
		id={"header"}
		xs={12}
		sm={12}
		lg={12}
		md={12}
		item
		container
	>
		<Grid
			xs={4}
			sm={4}
			lg={4}
			md={4}
			item
		>
			<Button
				onClick={() => props.history.push("/")}
			>
				MAIN PAGE
			</Button>
		</Grid>
		<Grid
			xs={4}
			sm={4}
			lg={4}
			md={4}
			item
		>
			<Button
				onClick={() => props.logout()}
			>
				LOGOUT
			</Button>
		</Grid>
	</Grid>
);

HeaderMenu.propTypes = {
	history: PropTypes.object.isRequired,
	logout: PropTypes.func.isRequired,
};

const mapDispatchToProps = dispatch => ({
	logout: () => dispatch(logout()),
});

export default connect(false, mapDispatchToProps)(HeaderMenu);