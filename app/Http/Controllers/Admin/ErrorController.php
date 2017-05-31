<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\DeleteErrorRequest;
use App\Models\Errors\Error;
use App\Models\Errors\JsError;
use App\Models\Errors\PhpError;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ErrorController extends Controller {
	public function show(Request $request) {
		$user = $request->user();
		return view('admin.errors', compact('user'));
	}

	public function getPhpErrors(Request $request) {
		$errors = PhpError::with('error.user');

		if ($request->has('sort')) {
			$sort = explode('|', $request->input('sort'));
			$errors = $errors->orderBy($sort[0], $sort[1]);

		} else {
			$errors = $errors->latest();
		}

		if ($request->has('filter')) {
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
	}

	public function getJsErrors(Request $request) {
		$errors = JsError::with('error.user');

		if ($request->has('sort')) {
			$errors = $errors->orderBy($request->input('sort'));
		} else {
			$errors = $errors->latest();
		}

		if ($request->has('filter')) {
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
	}

	public function delete(DeleteErrorRequest $deleteErrorRequest, Error $error){
		$deleteErrorRequest->commit($error);
		return response()->json([
			'status' => 'Success'
		]);
	}
}
