<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class DeleteUserRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool {
		if ($this->user() && $this->user()->exists()) {
			return true;
		}

		return false;
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

	/**
	 *
	 */
	public function commit(): void {
		$extended = $this->user()->user;
		$this->user()->delete();
		$extended->delete();
	}

}
