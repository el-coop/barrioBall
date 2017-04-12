<?php

namespace App\Http\Controllers;

use App\Models\Errors\Error;
use App\Models\Errors\JsError;
use Illuminate\Http\Request;

class ErrorController extends Controller
{
    public function store(Request $request){
    	if($request->ajax()){
			$error = new Error;
			$jsError = new JsError;
			if ($request->user()) {
				$error->user_id = $request->user->id;
			}
			$error->page = $request->input('page');
			$jsError->exception = json_encode([
				'message' => $request->input('message'),
				'source' => $request->input('source'),
				'line_number' => $request->input('lineNo'),
				'trace' => $request->input('trace')
			]);
			$jsError->user_agent = $request->input('userAgent');
			$jsError->save();
			$jsError->error()->save($error);
		}

		return response()->json([
			'status' => 'Success'
		]);
	}
}
