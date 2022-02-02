<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use JetBrains\PhpStorm\Pure;
use Throwable;

class PostRepository extends RepositoryApiEloquent
{

    /**
     * @inheritDoc
     */
    protected function model(): string
    {
        return Post::class;
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
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
        ]);

        if ($validate->fails()) {

            self::setResponseCode(400);
            self::setStatusResponse(400);

            return $validate->errors();
        }

        $data["user_id"] = Auth::user()->getAuthIdentifier();

        return Post::create($data);
    }

    /**
     * @param $id
     * @return array|mixed
     */
    public function find($id)
    {
        $post = Post::find($id);

        if(Auth::user() && $post->user_id === Auth::user()->getAuthIdentifier()) {
            return parent::find($id);
        }

        $post->views++;
        $post->save();

        return parent::find($id);
    }


}
