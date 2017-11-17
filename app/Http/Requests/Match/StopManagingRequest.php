<?php

namespace App\Http\Requests\Match;

use App\Events\Match\ManagerLeft;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StopManagingRequest extends FormRequest {
	protected $match;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool {

		$this->match = $this->route('match');

		return $this->user()->can('update', $this->match);
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
		$this->match->removeManager($this->user());
		event(new ManagerLeft($this->match, $this->user()));
	}


	/**
	 * @param Validator $validator
	 */
	public function withValidator(Validator $validator): void {

		$validator->after(function ($validator) {
			if ($this->match->managers()->count() < 2) {
				$validator->errors()->add('managers', __('match/requests.notEnoughManagers'));
			}
		});
	}
}
