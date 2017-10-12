<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogErrorRequest;
use Illuminate\Http\JsonResponse;

class ErrorController extends Controller
{
	/**
	 * @param LogErrorRequest $logErrorRequest
	 *
	 * @return JsonResponse
	 */
	public function store(LogErrorRequest $logErrorRequest): JsonResponse{
		$logErrorRequest->commit();

		return response()->json([
			'status' => 'Success'
		]);
	}
}
