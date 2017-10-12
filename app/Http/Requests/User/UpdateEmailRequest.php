<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEmailRequest extends FormRequest {
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
			'email' => 'required|email|max:255|unique:users'
		];
	}

	/**
	 *
	 */
	public function commit(): void {
		$this->user()->email = $this->input('email');
		$this->user()->save();
	}
}
