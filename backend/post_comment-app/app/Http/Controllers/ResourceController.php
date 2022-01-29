<?php

/**
 * @file     ResourceController.php
 * @author   Fabio William Conceição <messhias@gmail.com>
 * @since    15/03/2020
 * @version  1.0
 */


namespace App\Http\Controllers;


use App\Interfaces\ResourceControllerInterface;
use App\Traits\ResourceControllerTrait;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class ResourceController
 * @package App\Http\Controllers
 */
abstract class ResourceController extends Controller implements ResourceControllerInterface
{
	use ResourceControllerTrait;
	
	/**
	 * @var mixed
	 */
	protected mixed $repository;
	
	/**
	 * @var string
	 */
	protected string $viewsPath = "";
	
	/**
	 * @var string
	 */
	protected string $routesPath = "";
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return View
	 */
	public function index(): View
	{
		return view("{$this->getViewsPath()}.index", [
				"data" => $this->getRepository()->withPagination(),
			]
		);
	}
	
	/**
	 * @return string
	 */
	public function getViewsPath(): string
	{
		return $this->viewsPath;
	}
	
	/**
	 * @param string $viewsPath
	 */
	abstract public function setViewsPath(string $viewsPath): void;
	
	/**
	 * @return mixed
	 */
	public function getRepository(): mixed
	{
		return $this->repository;
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @param array $customData
	 * @return View
	 */
	public function create(array $customData = []): View
	{
		return view("{$this->getViewsPath()}.create", $customData);
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return RedirectResponse
	 */
	public function store(Request $request): RedirectResponse
	{
		try {
			$this->getRepository()->create($request->toArray());
			
			if ($this->getRepository()::getResponseCode() == 201) {
				return redirect()
					->route("{$this->getRoutesPath()}.index")
					->with("message", "Entry added successfully.");
			} else {
				return redirect()
					->route("{$this->getRoutesPath()}.index")
					->with("error", "Something went wrong :(.");
			}
		} catch (Exception $exception) {
			return $this->genericErrorTrait($exception, $this);
		}
	}
	
	/**
	 * @return string
	 */
	public function getRoutesPath(): string
	{
		return $this->routesPath;
	}
	
	/**
	 * @param string $routesPath
	 */
	abstract public function setRoutesPath(string $routesPath): void;
	
	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 * @return View
	 */
	public function show($id): View
	{
		return view("{$this->getViewsPath()}.show", [
			"data" => $this->getRepository()->find($id),
		]);
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param $id
	 * @param array $customData
	 * @return View
	 */
	public function edit($id, array $customData = []): View
	{
		$data = $this->getRepository()->find($id);
		return view("{$this->getViewsPath()}.edit", [
			$customData,
			'data' => $data,
		]);
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param Request $request
	 * @param int $id
	 * @return RedirectResponse
	 */
	public function update(Request $request, $id): RedirectResponse
	{
		try {
			$this->getRepository()->update($id, $request->toArray());
			
			if ($this->getRepository()::getResponseCode() === 201) {
				
				return redirect()
					->route("{$this->getRoutesPath()}.index", [
						'data' => $this->getRepository()->get(),
					])
					->with("message", "Updated successfully.");
			}
			
			return redirect()
				->route("{$this->getRoutesPath()}.index", [
					'data' => $this->getRepository()->get(),
				])
				->withErrors([
					"Something went wrong",
				]);
		} catch (Exception $exception) {
			return $this->genericErrorTrait($exception, $this);
		}
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return RedirectResponse
	 */
	public function destroy($id): RedirectResponse
	{
		try {
			$this->getRepository()->delete($id);
			if ($this->getRepository()::getResponseCode() == 200) {
				return redirect()
					->route("{$this->getRoutesPath()}.index")
					->with("message", "Entry deleted successfully");
			} else {
				return redirect()
					->route("{$this->getRoutesPath()}.index")
					->with("error", "Something went wrong");
			}
		} catch (Exception $exception) {
			return $this->genericErrorTrait($exception, $this);
		}
	}
}