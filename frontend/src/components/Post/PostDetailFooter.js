import {Grid} from "@mui/material";
import * as React from "react";

const PostDetailFooter = () => (
	<Grid
		xs={12}
		sm={12}
		lg={12}
		md={12}
		className={"post-details-footer-container"}
		item
		container
	>
		<Grid
			xs={6}
			sm={6}
			lg={6}
			md={6}
			direction={'row'}
			alignItems={"center"}
			alignContent={"center"}
			item
			container
		>
			POST
		</Grid>
		<Grid
			xs={6}
			sm={6}
			lg={6}
			md={6}
			direction={'row'}
			alignItems={"center"}
			alignContent={"center"}
			item
			container
		>
			??????
		</Grid>
	</Grid>
);

export default PostDetailFooter;