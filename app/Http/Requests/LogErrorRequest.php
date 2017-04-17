<?php

namespace App\Http\Requests;

use App\Models\Errors\Error;
use App\Models\Errors\JsError;
use Illuminate\Foundation\Http\FormRequest;

class LogErrorRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {

		if ($this->ajax()) {
			return true;
		}

		return false;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			//
		];
	}

	public function commit() {
		$error = new Error;
		$jsError = new JsError;
		if ($this->user()) {
			$error->user_id = $this->user->id;
		}
		$error->page = $this->input('page');
		$jsError->class = $this->input('message');
		$jsError->exception = json_encode([
			'message' => $this->input('message'),
			'source' => $this->input('source'),
			'line_number' => $this->input('lineNo'),
			'trace' => $this->input('trace')
		]);
		$jsError->user_agent = $this->input('userAgent');
		$jsError->vm = $this->input('vm');
		$jsError->save();
		$jsError->error()->save($error);
	}
}
