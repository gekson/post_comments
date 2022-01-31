<?php


namespace App\Resolvers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use \OwenIt\Auditing\Contracts\UserResolver as UserResolverContract;

/**
 * Class UserResolver
 * @package App\Resolvers
 */
class UserResolver implements UserResolverContract
{
	/**
	 * @inheritDoc
	 */
	public static function resolve()
	{
        $guards = Config::get('audit.user.guards');
        $user = null;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return Auth::guard($guard)->user();
            } else {
                if (Schema::hasTable("users") && auth()?->user()?->id) {
                    $user = User::find(
                        config(
                            'userid',
                            auth()?->user()?->id ?? null,
                        )
                    );
                }
                return $user;
            }
        }
	}
}
