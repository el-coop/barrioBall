<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\DeleteErrorRequest;
use App\Models\Errors\Error;
use App\Models\Errors\JsError;
use App\Models\Errors\PhpError;
use Cache;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ErrorController extends Controller {

	/**
	 * @return View
	 */
	public function show(): View {
		return view('admin.errors');
	}

	/**
	 * @param Request $request
	 *
	 * @return LengthAwarePaginator
	 */
	public function getPhpErrors(Request $request): LengthAwarePaginator {

		return Cache::tags('PHPError')->rememberForever(sha1($request->fullUrl()), function () use ($request) {
			$errors = PhpError::with('error.user');

			if ($request->filled('sort')) {
				$sort = explode('|', $request->input('sort'));
				$errors = $errors->orderBy($sort[0], $sort[1]);

			} else {
				$errors = $errors->latest();
			}

			if ($request->filled('filter')) {
				$filterVal = "%{$request->input('filter')}%";
				$errors->where(function ($query) use ($filterVal) {
					$query->where('message', 'like', $filterVal)
						->orWhere('created_at', 'like', $filterVal);
				})->orWhereHas('error', function ($query) use ($filterVal) {
					$query->where('page', 'like', $filterVal);
				})->orWhereHas('error.user', function ($query) use ($filterVal) {
					$query->where('email', 'like', $filterVal);
				});
			}

			return $errors->paginate($request->input('per_page'));

		});
	}

	/**
	 * @param Request $request
	 *
	 * @return LengthAwarePaginator
	 */
	public function getJsErrors(Request $request): LengthAwarePaginator {

		return Cache::tags('JSError')->rememberForever(sha1($request->fullUrl()), function () use ($request) {
			$errors = JsError::with('error.user');

			if ($request->filled('sort')) {
				$errors = $errors->orderBy($request->input('sort'));
			} else {
				$errors = $errors->latest();
			}

			if ($request->filled('filter')) {
				$filterVal = "%{$request->input('filter')}%";
				$errors->where(function ($query) use ($filterVal) {
					$query->where('class', 'like', $filterVal)
						->orWhere('created_at', 'like', $filterVal);
				})->orWhereHas('error', function ($query) use ($filterVal) {
					$query->where('page', 'like', $filterVal);
				})->orWhereHas('error.user', function ($query) use ($filterVal) {
					$query->where('email', 'like', $filterVal);
				});
			}

			return $errors->paginate($request->input('per_page'));
		});
	}

	/**
	 * @param DeleteErrorRequest $deleteErrorRequest
	 * @param Error $error
	 *
	 * @return JsonResponse
	 */
	public function delete(DeleteErrorRequest $deleteErrorRequest, Error $error): JsonResponse {
		$deleteErrorRequest->commit();

		Cache::tags($error->errorable_type)->flush();

		return response()->json([
			'status' => 'Success',
		]);
	}
}
