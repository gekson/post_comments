<?php

namespace App\Http\Controllers;

use App\Repositories\PostRepository;
use Illuminate\Http\JsonResponse;
use PHPUnit\Exception;

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

    protected function getPluralIdentifier(): string
    {
        return "posts";
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            return $this->defaultJSONResponse($this, $this->getRepository()->show($id));
        } catch (Exception $exception) {
            return $this->logError($exception);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function like($id): JsonResponse
    {
        try {
            return $this->defaultJSONResponse($this, $this->getRepository()->like($id));
        } catch (Exception $exception) {
            return $this->logError($exception);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function dislike($id): JsonResponse
    {
        try {
            return $this->defaultJSONResponse($this, $this->getRepository()->dislike($id));
        } catch (Exception $exception) {
            return $this->logError($exception);
        }
    }
}
