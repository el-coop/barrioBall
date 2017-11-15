<?php

namespace App\Http\Requests\Admin;

use App\Models\Errors\Error;
use Illuminate\Foundation\Http\FormRequest;

class DeleteErrorRequest extends FormRequest {
	protected $error;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool {
		$this->error = $this->route('error');

		return $this->user()->can('delete', $this->error);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array {
		return [
			//
		];
	}

	public function commit(): void {
		$this->error->errorable->delete();
	}
}
