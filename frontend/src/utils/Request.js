import axios from "axios";
import {getUserToken} from "./Helpers";

const options = {
	baseURL: process.env.REACT_APP_API,
	withCredentials: false,
	timeout: 3600,
	headers: {
		authorization: getUserToken()
	},
};

const Request = axios.create(options);

Request.parseParams = (params) => (
	Object.keys(params).map((key) =>
		[key, params[key]].map(encodeURIComponent).join("=")
	)
);

export default Request;