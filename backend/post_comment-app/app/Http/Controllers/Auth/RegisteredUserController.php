<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ResourceApiController;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegisteredUserController extends ResourceApiController
{
    /**
     * UsersController constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->setRepository($repository);
    }

    protected function getKeyIdentifier(): string
    {
        return "users";
    }

    protected function getSingularIdentifier(): string
    {
        return "User";
    }

    protected function getPluralIdentifier()
    {
        return "Users";
    }


    /**
     * Handle an incoming registration request.
     *
     * @param Request $request
     * @return JsonResponse
     *
     */
    public function store(Request $request): JsonResponse
    {
        if (!$this->checkKeyIdentifier($this, $request)) {
            return $this->defaultKeyIdentifierError();
        }

        try {
            return $this->defaultJSONResponse(
                $this,
                $this->getRepository()->register($request->input($this->getKeyIdentifier()))
            );
        } catch (Exception $exception) {
            return $this->logError($exception, "Something went wrong", $exception->getCode() ?? 500);
        }
    }
}
