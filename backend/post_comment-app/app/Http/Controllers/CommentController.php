<?php

namespace App\Http\Controllers;

use App\Repositories\CommentRepository;

class CommentController extends ResourceApiController
{
    /**
     * @param CommentRepository $repository
     */
    public function __construct(CommentRepository $repository)
    {
        $this->setRepository($repository);
    }

    protected function getKeyIdentifier(): string
    {
        return "comments";
    }

    protected function getSingularIdentifier(): string
    {
        return "comment";
    }

    protected function getPluralIdentifier(): string
    {
        return "comments";
    }
}
