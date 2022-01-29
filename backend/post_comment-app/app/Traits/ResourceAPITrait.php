<?php

/**
 * @file     ResourceAPITrait.php
 * @author   Fabio William Conceição <messhias@gmail.com>
 * @since    05/02/2020
 * @version  2.0
 *
 * @history  :
 *  2.0:
 *         - Added RepositoryApiEloquent as qualified attributes to methods scope.
 *         - Added ResourceApiController as qualified attributes to methods scope.
 */


namespace App\Traits;

use App\Http\Controllers\ResourceApiController;
use App\Repositories\RepositoryApiEloquent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Pure;

/**
 * Trait ResourceAPITrait
 * @package RestResources
 */
trait ResourceAPITrait
{
	/**
	 * Default trait function to treat the json responses and return it
	 * in a unified format to the API.
	 *
	 * @param ResourceApiController $controller
	 * @param mixed $data
	 * @param int|bool $customErrorResponse
	 * @return JsonResponse
	 */
	public function defaultJSONResponse(
		ResourceApiController $controller,
		mixed                 $data,
		int|bool              $customErrorResponse = false
	): JsonResponse
	{
		$responseCode = $controller->getRepository()::getResponseCode();
		
		if (is_int($customErrorResponse)) {
			$responseCode = $customErrorResponse;
		}
		
		if ($controller->getRepository()::getResponseCode() > 300) {
			$message = $controller->getRepository()::getResponseMessage();
		} else {
			$message = $controller->message($this->getRepository(),
				"{$controller->getPluralIdentifier()} – {$controller->foundMessage()}"
			);
		}
		
		return response()->json([
			"message" => $message,
			"data" => $data,
			"code" => $controller->getRepository()::getBodyCode(),
			"success" => $this->booleanResponses($controller->getRepository()),
			"error" => !$this->booleanResponses($controller->getRepository()),
			"completed_at" => now()->format("Y-m-d H:m:s"),
		], $responseCode);
	}
	
	/**
	 * Trait function to treat gracefully and customized
	 * for resource API context of returning messages based on
	 * plural context or singular.
	 *
	 * @param RepositoryApiEloquent $repository
	 * @param string $message
	 * @return string
	 */
	#[Pure] public function message(RepositoryApiEloquent $repository, string $message = ""): string
	{
		if ($repository instanceof RepositoryApiEloquent) {
			if ($this->booleanResponses($repository)) {
				return $message;
			} else {
				return "Check the data for error details";
			}
		} else {
			return "Invalid repository instance provided";
		}
	}
	
	/**
	 * Supportive trait function to treat the error, success
	 * and any boolean results based on status code retrieve
	 * by repository.
	 *
	 * @param RepositoryApiEloquent $repository
	 * @return bool
	 */
	#[Pure] public function booleanResponses(RepositoryApiEloquent $repository): bool
	{
		return match ($repository::getStatusResponse()) {
			201, 200 => true,
			default => false,
		};
	}
	
	/**
	 * @param ResourceApiController $controller
	 * @param Request $request
	 * @return bool
	 */
	public function checkKeyIdentifier(ResourceApiController $controller, Request $request): bool
	{
		if ($request->has($controller->getKeyIdentifier())) {
			return true;
		}
		
		return false;
	}
}
