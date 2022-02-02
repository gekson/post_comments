<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ResourceApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends ResourceApiController
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
     * Handle an incoming authentication request.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return $this->defaultJSONResponse(
            $this,
            [
                "success" => true,
                "data" => auth()->user(),
                "token" => auth()->user()->createToken('API Token')->plainTextToken
            ]
        );
    }

    /**
     * Destroy an authenticated session.
     *
     * @param Request $request
     * @return Response
     */
    public function destroy(Request $request): Response
    {
        auth()->user()?->tokens()->delete();

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        return response()->noContent();
    }
}
