<?php

namespace App\Http\Controllers;

use App\Repositories\PostRepository;

class PostController extends ResourceApiController
{
    /**
     * @param PostRepository $repository
     */
    public function __construct(PostRepository $repository)
    {
        $this->setRepository($repository);
    }

    protected function getKeyIdentifier(): string
    {
        return "posts";
    }

    protected function getSingularIdentifier(): string
    {
        return "post";
    }

    protected function getPluralIdentifier()
    {
        return "posts";
    }
}
