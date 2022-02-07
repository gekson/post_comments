import * as React from 'react';
import Card from '@mui/material/Card';
import CardHeader from '@mui/material/CardHeader';
import CardContent from '@mui/material/CardContent';
import CardActions from '@mui/material/CardActions';
import Avatar from '@mui/material/Avatar';
import IconButton from '@mui/material/IconButton';
import Typography from '@mui/material/Typography';
import { red } from '@mui/material/colors';
import MoreVertIcon from '@mui/icons-material/MoreVert';
import ThumbUpIcon from '@mui/icons-material/ThumbUp';
import ThumbDownIcon from '@mui/icons-material/ThumbDown';
import Button from "@mui/material/Button";
import VisibilityIcon from '@mui/icons-material/Visibility';
import PropTypes from "prop-types";

const PostCard = (props =  {
    thumbsUp: (id) => {},
    thumbsDown: (id) => {},
    find: (id) => {},
    comments: (id) => {},
}) => {

    return (
        <Card sx={{ maxWidth: 345 }}>
            <CardHeader
                avatar={
                    <Avatar sx={{ bgcolor: red[500] }} aria-label="recipe">
                        {props.p.title.charAt(0)}
                    </Avatar>
                }
                action={
                    <IconButton aria-label="settings">
                        <MoreVertIcon />
                    </IconButton>
                }
                title={props.p.title}
                subheader={props.p.updated_at}
            />
            {/*Show image*/}
            {/*<CardMedia*/}
            {/*    component="img"*/}
            {/*    height="194"*/}
            {/*    image="/static/images/cards/paella.jpg"*/}
            {/*    alt="Paella dish"*/}
            {/*/>*/}
            <CardContent>
                <Typography variant="body2" color="text.secondary">
                    {props.p.description}
                </Typography>
            </CardContent>
            <CardActions disableSpacing>
                <IconButton aria-label="thumbs up"
                            onClick={() => {
                                props.thumbsUp(props.p.id);
                            }}
                >
                    <ThumbUpIcon />
                    {props.p.like}
                </IconButton>
                <IconButton aria-label="thumbs down"
                            onClick={() => {
                                props.thumbsDown(props.p.id);
                            }}
                >
                    <ThumbDownIcon />
                    {props.p.dislike}
                </IconButton>
                <IconButton aria-label="Views"
                            onClick={() => {
                                props.find(props.p.id);
                            }}
                >
                    <VisibilityIcon />
                    {props.p.views}
                </IconButton>
                <Button size="small"
                        onClick={() => {
                            props.comments(props.p.id);
                        }}
                >
                    Comments
                </Button>
            </CardActions>
        </Card>
    );
};

PostCard.propTypes = {
    thumbsUp: PropTypes.func,
    thumbsDown: PropTypes.func,
};

export default PostCard;