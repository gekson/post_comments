import {Grid, Table, TableBody, TableCell, TableHead, TableRow} from "@mui/material";
import HeaderMenu from "../components/HeaderMenu";
import {connect, useDispatch} from "react-redux";
import React, {useCallback, useEffect, useState} from "react";
import {create, erase, get, update} from "../actions/Posts";
import DeleteIcon from "@material-ui/icons/Delete";
import Button from "@mui/material/Button";
import AddNewCommentForm from "../components/Comment/AddNewCommentForm";
import EditIcon from "@material-ui/icons/Edit";
import {GET_POST} from "../actions/Comment/types";

const ListPosts = (props) => {
    const {
            get = () => {
            },
            erase = (id) => {
            },
            create = (data = {}) => {
            },
            update = (data = {}) => {
            },
            posts = [],
            comments_errors = {},
            post = {id:0, title:''},
            loading_posts = false,
        } = props,
        [
            addFormOpen = false,
            handleFormOpen = () => {
            }
        ] = useState(false),
        [
            editTask,
            handleEditTask
        ] = useState(null),
        dispatch = useDispatch(),
        clearOnAdd = useCallback(() => dispatch({
                type: GET_POST,
                comments_errors: [],
            }),
            [dispatch]
        )
    let [description, handleDescriptionChange] = useState("");

    useEffect(() => {
        if (!addFormOpen) {
            get();
        }
        get();
    }, [
        get,
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
                        handleEditTask(null);
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
                        editTask={editTask}
                        name={description}
                        handleNameChange={handleDescriptionChange}
                    />
                    :
                    <Table>
                        <TableHead>
                            <TableRow>
                                <TableCell>
                                    ID
                                </TableCell>
                                <TableCell>
                                    Title
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
                            {posts.map(p =>
                                <TableRow
                                    key={p.id}
                                >
                                    <TableCell>
                                        {p.id}
                                    </TableCell>
                                    <TableCell>
                                        {p.title}
                                    </TableCell>
                                    <TableCell>
                                        {p.description}
                                    </TableCell>
                                    <TableCell>
                                        {p.formatted_created_date}
                                    </TableCell>
                                    <TableCell>
                                        <Button
                                            aria-controls="basic-menu"
                                            aria-haspopup="true"
                                            onClick={() => erase(p.id)}
                                        >
                                            <DeleteIcon/>
                                        </Button>
                                        <Button
                                            aria-controls="basic-menu"
                                            aria-haspopup="true"
                                            onClick={() => {
                                                handleDescriptionChange(p.description);
                                                handleEditTask(p);
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

const mapStateToProps = state => state.PostsStore;

const mapDispatchToProps = dispatch => ({
    get: () => dispatch(get()),
    erase: (id) => dispatch(erase(id)),
    create: (data = {}) => dispatch(create(data ?? false)),
    update: (id, data) => dispatch(update(id, data)),
});

export default connect(mapStateToProps, mapDispatchToProps)(ListPosts);