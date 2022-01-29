<?php

/**
 * @file     RepositoryEloquent.php
 * @author   Fabio William Conceição <messhias@gmail.com>
 * @since    05/02/2020
 * @version  1.0
 */


namespace App\Repositories;


use Exception;
use Illuminate\Container\Container as App;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

/**
 * Default eloquent repository class to handle
 * all the commons operations between models and repositories.
 *
 * Class RepositoryEloquent
 * @package RestResources
 */
abstract class RepositoryApiEloquent extends RepositoryEloquent
{

	/**
	 * @var int
	 */
	static int $statusResponse = 200;

	/**
	 * @var int
	 */
	static int $bodyCode = 200;

	/**
	 * @var string
	 */
	protected static string $responseMessage = "";

	/**
	 * RepositoryApiEloquent constructor.
	 * @param App $app
	 * @throws Exception
	 */
	public function __construct(App $app)
	{
		$app = new App();
		parent::__construct($app);
		/**
		 * By the default all the applications it'll return the 200 status code
		 */
		self::setResponseCode(200);
		self::setStatusResponse(200);
	}

	/**
	 * @return int
	 */
	public static function getStatusResponse(): int
	{
		return self::$statusResponse;
	}

	/**
	 * @param int $statusResponse
	 */
	public static function setStatusResponse(int $statusResponse): void
	{
		self::$statusResponse = $statusResponse ?? self::getResponseCode();
	}

	/**
	 * @return string
	 */
	public static function getResponseMessage(): string
	{
		return self::$responseMessage;
	}

	/**
	 * @param string $responseMessage
	 */
	public static function setResponseMessage(string $responseMessage): void
	{
		self::$responseMessage = $responseMessage;
	}

	/**
	 * @return int
	 */
	public static function getBodyCode(): int
	{
		return self::$bodyCode;
	}

	/**
	 * @param int $bodyCode
	 */
	public static function setBodyCode(int $bodyCode = 200)
	{
		self::$bodyCode = $bodyCode;
	}

	/**
	 * @return false|Authenticatable
	 */
	public function me()
	{
		try {
			if (!Auth::user()) {
				self::setStatusResponse(404);
			}

			return Auth::user() ?? false;
		} catch (Exception $exception) {
			self::setResponseCode($exception->getCode());
			self::setStatusResponse($exception->getCode());
			self::setResponseMessage("You need log in to see this information");
			return false;
		}
	}
}
