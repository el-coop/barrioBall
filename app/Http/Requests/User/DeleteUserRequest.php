<?php

namespace App\Http\Requests\User;

use App\Events\User\Deleted;
use Illuminate\Foundation\Http\FormRequest;

class DeleteUserRequest extends FormRequest {
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
			//
		];
	}

	/**
	 *
	 */
	public function commit(): void {
		$user = $this->user()->load('joinRequests','playedMatches','managedMatches');
		$user->delete();
		event(new Deleted($user));
	}

}
