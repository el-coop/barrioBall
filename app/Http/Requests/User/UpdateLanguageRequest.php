<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLanguageRequest extends FormRequest {
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
			'language' => 'required|in:en,es',
		];
	}

	/**
	 *
	 */
	public function commit(): void {

		$this->user()->language = $this->input('language');
		$this->user()->save();
	}
}
