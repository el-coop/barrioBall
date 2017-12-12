<?php

namespace App\Http\Requests\Misc;

use App\Events\Misc\ContactUsSent;
use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array {
		return [
			'email' => 'required|email',
			'subject' => 'required|string|in:help,feedback,coding,translation,contribute,other',
			'message' => 'required|min:10',
		];
	}

	/**
	 *
	 */
	public function commit(): void {
		event(new ContactUsSent($this->input('email'), $this->input('subject'), $this->input('message')));
	}
}
