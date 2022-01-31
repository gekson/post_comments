<?php

namespace App\Repositories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use JetBrains\PhpStorm\Pure;

class CommentRepository extends RepositoryApiEloquent
{

    /**
     * @inheritDoc
     */
    protected function model(): string
    {
        return Comment::class;
    }

    /**
     * @inheritDoc
     */
    #[Pure] protected function module(): string
    {
        return $this->model();
    }

    /**
     * @param array $data
     * @return mixed
     * @throws Throwable
     */
    public function create(array $data = []): mixed
    {
        $validate = Validator::make($data, [
            'post_id' => ['required', 'integer', 'exists:posts,id'],
            'description' => ['required', 'string'],
        ]);

        if ($validate->fails()) {

            self::setResponseCode(400);
            self::setStatusResponse(400);

            return $validate->errors();
        }

        $data["user_id"] = Auth::user()->getAuthIdentifier();

        return Comment::create($data);
    }

    /**
     *@inheritDoc
     */
    public function update($id, array $data = []): Model|bool|array
    {
        $validate = Validator::make($data, [
            'post_id' => ['required', 'integer', 'exists:posts,id'],
        ]);

        if ($validate->fails()) {
            self::setResponseCode(400);
            self::setStatusResponse(400);

            return $validate->errors()->getMessages();
        }

        $comment = Comment::find($id);
        if (empty($comment)) {
            self::setResponseCode(404);
            return [
                "code" => 404,
                "message" => "Record not found.",
            ];
        }

        if($comment->post_id !== $data['post_id']) {
            self::setResponseCode(400);
            self::setStatusResponse(400);

            return [
                "code" => 400,
                "message" => "This comment does not belong to this post.",
            ];
        }

        return parent::update($id, $data);
    }

    public function delete($id, $validateUser = false)
    {
        $comment = Comment::where(["id" => $id])->with("post")->get();

        if (count($comment) == 0) {
            self::setResponseCode(404);
            return [
                "code" => 404,
                "message" => "Record not found.",
            ];
        }

        if ($comment[0]->user_id !== Auth::user()->getAuthIdentifier() &&
            $comment[0]->post->user_id !== Auth::user()->getAuthIdentifier()) {

            self::setResponseCode(401);
            return [
                "code" => 404,
                "message" => "This users can't delete this record.",
            ];
        }

        return parent::delete($id, false);
    }

}

