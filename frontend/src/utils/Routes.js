import {BrowserRouter as Router, Redirect, Route, Switch} from "react-router-dom";
import Pages from "../Pages";
import {getUserToken} from "./Helpers";

const MountRoutes = () => {
	const privateRoutes = {
		"/": Pages.MainPage,
		"/main-page": Pages.MainPage,
		"/comment": Pages.Comment,
		"/posts/add": Pages.AddNewTask,
		"/posts/list": Pages.ListPosts,
	};

	const publicRoutes = {
		"/login": Pages.Login,
		"/register": Pages.Register,
	};

	return [
		...Object.entries(publicRoutes).map(([path, component]) =>
			<Route
				exact
				path={path}
				component={component}
				key={path}
			/>
		),
		...Object.entries(privateRoutes).map(([path, component]) => {
			if (getUserToken() === "") {
				return (
					<Redirect
						to={{pathname: "/login"}}
						key={"login"}
					/>
				);
			}
			return (
				<Route
					exact
					path={path}
					component={component}
					key={path}
				/>
			);
		}),
		<Redirect
			to={{
				pathname: "/login"
			}}
			key={"login"}
		/>
	];
}

const Routes = () => (
	<Router basename={"/"}>
		<Switch>
			{MountRoutes()}
		</Switch>
	</Router>
);

export default Routes;