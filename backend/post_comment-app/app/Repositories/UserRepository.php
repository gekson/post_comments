<?php

namespace App\Repositories;

use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use JetBrains\PhpStorm\Pure;

/**
 * Class UserRepository
 * @package App\Repositories\Admin
 */
class UserRepository extends RepositoryApiEloquent
{
    /**
     * @param array $data
     * @return bool|array|string
     */
    public function register(array $data = []): bool|array|string
    {
        $validate = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($validate->fails()) {

            self::setResponseCode(400);
            self::setStatusResponse(400);

            return $validate->errors();
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return [
            "success" => true,
            "data" => $user,
            "token" => $user->createToken('API Token')->plainTextToken,
        ];
    }

	/**
	 * @param array $data
	 * @return bool|array|string
	 * @throws Exception
	 */
	public function login(array $data = []): bool|array|string
	{
		if (empty($data)) {
			self::setStatusResponse(400);
			self::setResponseCode(400);
			return "Please provide valid credentials.";
		}

		if (!isset($data['email'])) {
			self::setStatusResponse(400);
			self::setResponseCode(400);
			return "Please provide valid e-mail.";
		}

		if (!isset($data['password'])) {
			self::setStatusResponse(400);
			self::setResponseCode(400);
			return "Please provide valid password.";
		}

		$user = $this->getModel()
			->where([
				'email' => $data['email']
			])
			->first();

		if (is_null($user)) {
			self::setStatusResponse(400);
			self::setResponseCode(400);
			return "No user found with this credentials.";
		}

		if (password_verify($data['password'], $user->password)) {
            return [
                "success" => true,
                "token" => auth("web")->login($user),
            ];
		}

		return false;
	}

	/**
	 * @inheritDoc
	 */
	#[Pure] protected function module(): string
	{
		return $this->model();
	}

	/**
	 * @inheritDoc
	 */
	protected function model(): string
	{
		return User::class;
	}
}
