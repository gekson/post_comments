<?php

/**
 * @file     RepositoryEloquent.php
 * @author   Fabio William Conceição <messhias@gmail.com>
 * @since    15/03/2020
 * @version  1.0
 */

namespace App\Repositories;


use App\Interfaces\RepositoryInterface;
use Exception;
use Illuminate\Container\Container as App;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Default eloquent repository class to handle
 * all the commons operations between models and repositories.
 *
 * Class RepositoryEloquent
 * @package App\Repositories
 */
abstract class RepositoryEloquent
{
	/**
	 * @var int
	 */
	protected static int $responseCode = 200;

	/**
	 * Module representation.
	 *
	 * @var mixed
	 */
	protected $module;

	/**
	 * Model.
	 *
	 * @var mixed
	 */
	protected $model;

	/**
	 * Application container.
	 *
	 * @var App
	 */
	protected App $app;

	/**
	 * Model object representation.
	 *
	 * @var mixed
	 */
	protected $obj;

	/**
	 * RepositoryEloquent constructor.
	 *
	 * @param App $app
	 *
	 * @throws Exception
	 */
	public function __construct(App $app)
	{
		$this->setApp($app);
		$this->makeModel();
	}

	/**
	 * Creating the model object instance.
	 *
	 * @return mixed
	 * @throws BindingResolutionException
	 * @throws Exception
	 */
	protected function makeModel(): mixed
	{
		$model = $this->getApp()->make($this->model());
		$this->setModule($this->module());

		if (!$model instanceof Model) {
			throw new Exception("Class {$this->model()} must be an instance of Model");
		}

		$this->setModel($model);
		return $this->getModel();
	}

	/**
	 * Returning the application container.
	 *
	 * @return App
	 */
	public function getApp(): App
	{
		return $this->app;
	}

	/**
	 * Set up application container.
	 *
	 * @param App $app
	 */
	public function setApp(App $app): void
	{
		$this->app = $app;
	}

	/**
	 * Abstract set up model function.
	 *
	 * @return mixed
	 */
	abstract protected function model(): string;

	/**
	 * Abstract set up module representation.
	 *
	 * @return mixed
	 */
	abstract protected function module(): string;

	/**
	 * Returning the set up model.
	 *
	 * @return mixed
	 */
	public function getModel()
	{
		return $this->model;
	}

	/**
	 * Set up the model into object context.
	 *
	 * @param mixed $model
	 */
	public function setModel($model): void
	{
		$this->model = $model;
	}

	/**
	 * @return int
	 */
	public static function getResponseCode(): int
	{
		return self::$responseCode;
	}

	/**
	 * @param int $responseCode
	 */
	public static function setResponseCode(int $responseCode): void
	{
		self::$responseCode = $responseCode;
	}

	/**
	 * Returning the object model.
	 *
	 * @return mixed
	 */
	public function getObj()
	{
		return $this->obj;
	}

	/**
	 * Set up the object model.
	 *
	 * @param mixed $obj
	 */
	public function setObj(mixed $obj): void
	{
		$this->obj = $obj;
	}

	/**
	 * Returning the module.
	 *
	 * @return mixed
	 */
	public function getModule(): mixed
	{
		return $this->module;
	}

	/**
	 * Set up the module.
	 *
	 * @param mixed $module
	 */
	public function setModule(mixed $module): void
	{
		$this->module = $module;
	}

	/**
	 * Returning all the collection.
	 *
	 * @return mixed
	 */
	public function all()
	{
		return $this->get();
	}

	/**
	 * Returning all collection.
	 *
	 * @return mixed
	 */
	public function get(): mixed
	{
		return $this->getModel()->get();
	}

	/**
	 * Returning an where based on specific filter.
	 *
	 * It's seems work in the same way as model but there's a difference
	 * since this function create a log entry in database.
	 *
	 * @param array $filter
	 * @return mixed
	 */
	public function where(array $filter = []): mixed
	{
		return $this->model->where($filter)->get();
	}

	/**
	 * Create a new collection.
	 *
	 * @param array $data
	 * @return mixed
	 * @throws Throwable
	 */
	public function create(array $data = []): mixed
	{
		$this->obj = new $this->model;

		return DB::transaction(function () use ($data) {
			return $this->saveObj(data: $data, creating: true);
		});
	}

	/**
	 * Saving the object instance.
	 *
	 * @param array $data
	 * @param bool $creating
	 * @param bool $deleting
	 * @param bool $updating
	 * @return bool|Model
	 */
	protected function saveObj(
		array $data = [],
		bool  $creating = false,
		bool  $deleting = false,
		bool  $updating = false,
	): Model|bool
	{
		$this->syncData($data);
		$this->beforeSave($this->obj);

		if ($this->obj->save()) {
			$data = $this->afterSave(
				model: $this->obj,
				data: $data,
				creating: $creating,
				updating: $updating,
				deleting: $deleting
			);
			if ($creating) {
				self::setResponseCode(201);
			} else {
				self::setResponseCode(200);
			}
			return $data;
		}

		return false;
	}

	/**
	 * Sync object model data.
	 *
	 * @param mixed $data
	 * @return void
	 */
	protected function syncData(array &$data = []): void
	{
		if (!$this->obj) {
			return;
		}

		$this->obj->setRawAttributes($this->getDefaultData());
		$fields = $this->model->getFillable();

		array_map(function ($field) use ($data) {
			if (array_key_exists($field, $data)) {
				$this->obj->{$field} = $data[$field];
			}
		}, $fields);
	}

	/**
	 * Returning the object model default data.
	 *
	 * @return mixed
	 */
	protected function getDefaultData(): mixed
	{
		return $this->obj->getAttributes();
	}

	/**
	 * Before save returning the object sync data of collections instances.
	 *
	 * @param Model $model
	 * @return Model
	 */
	protected function beforeSave(Model &$model): Model
	{
		return $model;
	}

	/**
	 * After save returning the object sync data of collections instances.
	 *
	 * In this method we'll sync all the relationships in the case of the request
	 * full the models relationships instances.
	 *
	 * For example, the model XPTO has the relationship x and in the request instance the
	 * x method is filled it'll synced automatically to the model data.
	 *
	 * Evolution of ORM relationship.
	 *
	 * @param Model $model
	 * @param array $data
	 * @param bool $creating
	 * @param bool $updating
	 * @param bool $deleting
	 * @return Model
	 */
	protected function afterSave(
		Model $model,
		array $data,
		bool  $creating = false,
		bool  $updating = false,
		bool  $deleting = false,
	): Model
	{
		$relationships = [];
		foreach ($model->getRelations() as $r => $relationship) {
			if (array_key_exists($r, $data)) {
				$relationships[$r] = $data[$r];
			}
		}


		if (count($relationships) > 0) {
			foreach ($relationships as $key => $d) {
				$model->$key()->sync(
					data: $d,
					create: $creating,
					update: $updating,
					deleting: $deleting,
				);
			}

			if ($creating) {
				$model->save();
			} else if ($updating) {
				$model->update();
			}
		}

		return $model;
	}

	/**
	 * Updating an collection.
	 *
	 * @param $id
	 * @param array $data
	 *
	 * @return array|bool|Model
	 * @throws Throwable
	 */
	public function update($id, array $data = []): Model|bool|array
	{
		$this->obj = $this->find($id, true);

		$save_data = false;

		if (!empty($this->obj)) {
            if($this->obj->user_id !== Auth::user()->getAuthIdentifier()) {
                self::setResponseCode(401);
                return [
                    "code" => 404,
                    "message" => "This users can't update this record.",
                ];
            }

			self::setResponseCode(200);
			DB::beginTransaction();
			try {
				$save_data = $this->saveObj(
					data: $data,
					updating: true,
				);
				DB::commit();
			} catch (Exception $e) {
				Log::error($e);
				DB::rollBack();

				return false;
			}
		}

		if (!$save_data) {
			self::setResponseCode(404);
            return [
                "code" => 404,
                "message" => "Record not found.",
            ];
		}

		return $save_data;
	}

	/**
	 * Find a specific model instance.
	 *
	 * @param      $id
	 *
	 * @return mixed
	 */
	public function find($id)
	{
		$this->obj = $this->model->find($id);

		if (!$this->obj) {
			return [];
		}

		return $this->obj;
	}

	/**
	 * @param $id
	 * @param array $relationships
	 * @return mixed
	 */
	public function findWithRelationship($id, array $relationships = []): mixed
	{

		return $this->getModel()->with($relationships)->find($id);
	}

	/**
	 * Delete an instance.
	 *
	 * @param $id
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function delete($id, $validateUser = true)
	{
		$obj = $this->find($id);

		if (!is_array($obj)) {
            if($validateUser && $this->obj->user_id !== Auth::user()->getAuthIdentifier()) {
                self::setResponseCode(401);
                return [
                    "code" => 404,
                    "message" => "This users can't delete this record.",
                ];
            }

			DB::beginTransaction();
			try {
				if ($obj->active) {
					$obj->active = false;
					$obj->save();
					$obj->refresh();
				}
				$delete = $obj->delete();
				DB::commit();

				return $delete;
			} catch (Exception $exception) {
				Log::error($exception);
				DB::rollBack();

				return false;
			}
		} else {
            self::setResponseCode(404);
            return [
                "code" => 404,
                "message" => "Record not found.",
            ];
        }

		return true;
	}

	/**
	 * Running the validate functions.
	 *
	 * @return bool
	 */
	public function validate(): bool
	{
		if (!$this->obj) {
			return false;
		}

		return method_exists($this->obj, 'isValid') ? $this->obj->isValid() : false;
	}

	/**
	 * Returning the active collections.
	 *
	 * @return mixed
	 */
	public function active()
	{
		return $this->model->where('active', "true")->get();
	}

	/**
	 * Return all the active entries with default
	 * eloquent pagination.
	 *
	 * @return mixed
	 */
	public function activeWithPagination(): mixed
	{
		return $this->model->where('active', "true")->paginate();
	}

	/**
	 * Generic filter.
	 * This filter returns all the object collections which satisfy the filter
	 * criteria.
	 *
	 * @param array $filter
	 * @return mixed
	 */
	public function filter(array $filter)
	{
		return $this->model->where($filter)->get();
	}

	/**
	 * Returning only one object collection instance.
	 *
	 * @param array $filter
	 * @return mixed
	 */
	public function filterOne(array $filter)
	{
		return $this->model->where($filter)->first();
	}

	/**
	 * Retrieve the data.
	 *
	 * @return mixed
	 */
	public function first()
	{
		return $this->getModel()
			->first();
	}

	/**
	 * Return the models entry with default pagination
	 *
	 * @return mixed
	 */
	public function withPagination()
	{
		return $this->getModel()
			->paginate();
	}

	/**
	 * @return mixed
	 */
	public function getFirstActive()
	{
		return $this->getModel()
			->where("active", "true")
			->first();
	}

	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function paginate(Request $request): mixed
	{
		return $this->getModel()
			->paginate();
	}

	/**
	 * @param array $relationships
	 * @param array $where
	 * @return mixed
	 */
	public function getWithRelationships(array $relationships = [], array $where = []): mixed
	{
		$data = $this->getModel();

		if (count($relationships) > 0) {
			$data = $this->getModel()
				->with($relationships);
		}

		if (count($where) > 0) {
			$data = $data->where($where);
		}

		return $data->get();
	}

	/**
	 * Before delete returning the object sync data of collections instances.
	 *
	 * Before the delete itself we need get all the relationship of the model instances
	 * and delete all the relationships to avoid noise in the database.
	 *
	 * @param Model $model
	 * @return Model
	 */
	protected function beforeDelete(Model $model): Model
	{
		$relationships = [];
		foreach ($model->relationships() as $r => $relationship) {
			$relationships[$r] = $relationship;
		}

		if (count($relationships) > 0) {
			foreach ($relationships as $key => $d) {
				$model->$key()->sync($d, true, false, false);
				$model->$key()->sync(
					data: $d,
					deleting: true,
				);
			}
			$model->delete();
		}

		return $model;
	}
}
