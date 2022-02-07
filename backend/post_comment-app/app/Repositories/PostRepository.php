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
//            'image' => ['mimes:jpeg,png']
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
     * @return mixed
     */
    public function show($id): mixed
    {
        $post = Post::find($id);

        if(Auth::user() && $post?->user_id === Auth::user()->getAuthIdentifier()) {
            return parent::find($id);
        }

        $post->views++;
        $post->save();

        return parent::find($id);
    }

    /**
     * @param $id
     * @return array|mixed
     */
    public function like($id)
    {
        $post = Post::find($id);

        if(Auth::user() && $post->user_id === Auth::user()->getAuthIdentifier()) {
            return $post;
        }

        $post->like++;
        $post->save();

        return $post;
    }

    /**
     * @param $id
     * @return array|mixed
     */
    public function dislike($id)
    {
        $post = Post::find($id);

        if(Auth::user() && $post->user_id === Auth::user()->getAuthIdentifier()) {
            return $post;
        }

        $post->dislike++;
        $post->save();

        return $post;
    }

    /**
     * @param array $relationships
     * @param array $where
     * @return mixed
     */
    public function getWithRelationships(array $relationships = [], array $where = []): mixed
    {
        $relationships = ['user', 'comments'];
        return parent::getWithRelationships($relationships, $where);
    }

    public function findWithRelationship($id, array $relationships = []): mixed
    {
        $relationships = ['user', 'comments'];
        return parent::findWithRelationship($id, $relationships);
    }


}
