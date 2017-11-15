<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUsernameRequest extends FormRequest {
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
			'username' => 'required|max:255|unique:users'
		];
	}

	/**
	 *
	 */
	public function commit(): void {

		$this->user()->username = $this->input('username');
		$this->user()->save();
	}
}
