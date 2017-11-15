<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool {
		return !! $this->user();
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array {
		return [
			'password' => 'required|min:6|confirmed',
		];
	}


	/**
	 *
	 */
	public function commit(): void {

		$this->user()->password = bcrypt($this->input('password'));
		$this->user()->save();
	}
}
