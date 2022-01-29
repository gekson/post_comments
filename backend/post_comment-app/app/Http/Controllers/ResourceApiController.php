<?php

namespace App\Http\Controllers;

use App\Repositories\RepositoryApiEloquent;
use App\Traits\GenericLogErrors;
use App\Traits\ResourceAPITrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

/**
 * Class ResourceApiController
 * @package App\Http\Controllers
 */
abstract class ResourceApiController extends Controller
{
    use ResourceAPITrait,
        GenericLogErrors;

	/**
	 * Representation of the repository in the abstract context.
	 *
	 * @var mixed
	 */
	protected RepositoryApiEloquent|null $repository = null;

	/**
	 * Overriding the call action controller method to
	 * check if the repository was set, otherwise
	 * trigger a default JSON error to keep
	 * the application running without FATAL exception.
	 *
	 * @param string $method
	 * @param array $parameters
	 * @return mixed
	 */
	public function callAction($method, $parameters): mixed
	{
		if ($this->getRepository() == null) {
			$exception = new Exception("No repository was set");

			return $this->logError($exception, "No repository was set");
		}

		return parent::callAction($method, $parameters);
	}

	/**
	 * Returns the repository representation.
	 *
	 * @return RepositoryApiEloquent
	 */
	public function getRepository(): RepositoryApiEloquent
	{
		return $this->repository;
	}

	/**
	 * Set up the repository into the abstract context.
	 *
	 * @param RepositoryApiEloquent $repository
	 */
	public function setRepository(RepositoryApiEloquent $repository): void
	{
		$this->repository = $repository;
	}

	/**
	 * Create new entity based on repository abstraction.
	 * {
	 *   xxxx:
	 * }
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function create(Request $request): JsonResponse
	{
		if (!$request->input($this->getKeyIdentifier())) {
			return $this->defaultKeyIdentifierError();
		}

		try {
			return $this->defaultJSONResponse(
				$this,
				$this->getRepository()->create(
					$request->input($this->getKeyIdentifier()))
			);

		} catch (Exception $exception) {
			$code = (int)$exception->getCode();
			if ($code <= 0) {
				$code = 500;
			}

			return $this->logError($exception, "Something went wrong", $code);
		}
	}

	/**
	 * Set up the key identifier for the controller.
	 *
	 * @return mixed
	 */
	abstract protected function getKeyIdentifier(): string;

	/**
	 * Return the entity base on repository abstraction.
	 *
	 * @param  $id
	 *
	 * @return JsonResponse
	 */
	public function find($id): JsonResponse
	{
		if (empty($id)) {
			return $this->defaultKeyIdentifierError();
		}

		try {
			return $this->defaultJSONResponse(
				$this,
				$this->getRepository()->find($id),
			);
		} catch (Exception $exception) {

			$code = (int)$exception->getCode();
			if ($code <= 0) {
				$code = 500;
			}

			return $this->logError($exception, "Something went wrong", $code);
		}
	}

	/**
	 * @return JsonResponse
	 */
	public function get(): JsonResponse
	{
		try {
			return $this->defaultJSONResponse(
				$this,
				unserialize(
					str_replace(array('NAN;', 'INF;'), '0;', serialize($this->getRepository()->get()))),
			);

		} catch (Exception $exception) {

			$code = (int)$exception->getCode();
			if ($code <= 0) {
				$code = 500;
			}

			return $this->logError($exception, "Something went wrong", $code);
		}
	}

	/**
	 * Update entity base on id provided and database sent of the repository
	 * representation.
	 *
	 * @param Request $request
	 * @param mixed $id
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function update(Request $request, mixed $id): JsonResponse
	{
		if (empty($id)) {
			return $this->defaultKeyIdentifierError();
		}

		if (!$this->checkKeyIdentifier($this, $request)) {
			return $this->defaultKeyIdentifierError();
		}

		try {
			return $this->defaultJSONResponse(
				$this,
				$this->getRepository()->update(
					$id,
					$request->input($this->getKeyIdentifier()))
			);
		} catch (Exception $exception) {

			$code = (int)$exception->getCode();
			if ($code <= 0) {
				$code = 500;
			}

			return $this->logError($exception, "Something went wrong", $code);
		}
	}

	/**
	 * Delete entity based on repository implementation
	 *
	 * @param mixed $id
	 *
	 * @return JsonResponse
	 */
	public function delete($id): JsonResponse
	{
		if (empty($id)) {
			return $this->defaultKeyIdentifierError();
		}

		try {
			return $this->defaultJSONResponse(
				$this,
				$this->getRepository()->delete($id),
			);
		} catch (Exception $exception) {

			$code = (int)$exception->getCode();
			if ($code <= 0) {
				$code = 500;
			}

			return $this->logError($exception, "Something went wrong", $code);
		}
	}

	/**
	 * Default request entries with pagination.
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function paginate(Request $request): JsonResponse
	{
		try {
			return $this->defaultJSONResponse(
				$this,
				$this->getRepository()
					->paginate($request),
			);
		} catch (Exception $exception) {

			$code = (int)$exception->getCode();
			if ($code <= 0) {
				$code = 500;
			}

			return $this->logError($exception, "Something went wrong", $code);
		}
	}

	/**
	 * @return JsonResponse
	 */
	public function active(): JsonResponse
	{
		try {
			return $this->defaultJSONResponse(
				$this,
				$this->getRepository()->active(),
			);
		} catch (Exception $exception) {

			$code = (int)$exception->getCode();
			if ($code <= 0) {
				$code = 500;
			}

			return $this->logError($exception, "Something went wrong", $code);
		}
	}

	/**
	 * @return JsonResponse
	 */
	public function me(): JsonResponse
	{
		try {
			return $this->defaultJSONResponse(
				$this,
				$this->getRepository()->me(),
			);
		} catch (Exception $exception) {

			$code = (int)$exception->getCode();
			if ($code <= 0) {
				$code = 500;
			}

			return $this->logError($exception, "Something went wrong", $code);
		}
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function login(Request $request): JsonResponse
	{
		if (!$request->input($this->getKeyIdentifier())) {
			return $this->defaultKeyIdentifierError();
		}

		try {
			return $this->defaultJSONResponse(
				$this,
				$this->getRepository()->login(
					$request->input($this->getKeyIdentifier()))
			);

		} catch (Exception $exception) {

			$code = (int)$exception->getCode();
			if ($code <= 0) {
				$code = 500;
			}

			return $this->logError($exception, "Something went wrong", $code);
		}
	}

	/**
	 * @return JsonResponse
	 */
	public function getFirstActive(): JsonResponse
	{
		try {
			return $this->defaultJSONResponse(
				$this,
				$this->getRepository()->getFirstActive()
			);
		} catch (Exception $exception) {
			$code = (int)$exception->getCode();
			if ($code <= 0) {
				$code = 500;
			}

			return $this->logError($exception, "Something went wrong", $code);
		}
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function filter(Request $request): JsonResponse
	{
		try {
			return $this->defaultJSONResponse(
				$this,
				$this->getRepository()->filter($request),
			);
		} catch (Exception $exception) {
			return $this->logError($exception);
		}
	}

	/**
	 * @param Request $request
	 * @param string $token
	 * @return JsonResponse
	 */
	public function verifyEmail(Request $request, string $token = ""): JsonResponse
	{
		if (!$this->checkKeyIdentifier($this, $request)) {
			return $this->defaultKeyIdentifierError();
		}

		try {
			return $this->defaultJSONResponse(
				$this,
				$this->getRepository()
					->verifyEmail(
						$request->input($this->getKeyIdentifier()),
						$token
					)
			);
		} catch (Exception $exception) {
			return $this->logError($exception);
		}
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function getWithRelationships(Request $request): JsonResponse
	{
		try {
			return $this->defaultJSONResponse(
				$this,
				$this->getRepository()
					->getWithRelationships($request->all())
			);
		} catch (Exception $exception) {
			return $this->logError($exception);
		}
	}

	/**
	 * @param Request $request
	 * @param $id
	 * @return JsonResponse
	 */
	public function findWithRelationships(Request $request, $id): JsonResponse
	{
		try {
			return $this->defaultJSONResponse(
				$this,
				$this->getRepository()
					->findWithRelationship($id, $request->all())
			);
		} catch (Exception $exception) {
			return $this->logError($exception);
		}
	}

	/**
	 * Set up an singular identifier for the class context process.
	 *
	 * @return mixed
	 */
	abstract protected function getSingularIdentifier(): string;

	/**
	 * Set up a plural identifier for the class context process.
	 */
	abstract protected function getPluralIdentifier();

	/**
	 * @return JsonResponse
	 */
	protected function defaultNotLoggedResponse(): JsonResponse
	{
		return response()->json([
			"data" => false,
			"message" => "Only logged in users can fetch this content.",
			"code" => 401,
			"success" => false,
			"error" => true,
			"completed_at" => now()->format("Y-m-d H:m:s"),
		], 401);
	}

    /**
     * @param ResourceApiController $controller
     * @param Request $request
     * @return bool
     */
    protected function checkKeyIdentifier(ResourceApiController $controller, Request $request): bool
    {
        if ($request->has($controller->getKeyIdentifier())) {
            return true;
        }

        return false;
    }

    /**
     * @param string $customMessage
     * @return JsonResponse
     */
    protected function defaultKeyIdentifierError(string $customMessage = ""): JsonResponse
    {
        $msg = "Please provide the request key identifier.";

        if (!empty($customMessage)) {
            $msg = $customMessage;
        }

        return response()
            ->json([
                "message" => $msg,
                "error" => true,
                "data" => false,
                "success" => false,
                "code" => 400,
            ], 400);
    }
}
