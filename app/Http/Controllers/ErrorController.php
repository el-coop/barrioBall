<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogErrorRequest;
use App\Models\Errors\Error;
use App\Models\Errors\JsError;
use Illuminate\Http\Request;

class ErrorController extends Controller
{
    public function store(LogErrorRequest $logErrorRequest){
		$logErrorRequest->commit();

		return response()->json([
			'status' => 'Success'
		]);
	}
}
