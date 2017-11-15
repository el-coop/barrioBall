<?php

namespace App\Http\Requests\Match;

use App\Events\Match\PlayerLeft;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class LeaveMatchRequest extends FormRequest {
	protected $match;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool {
		$this->match = $this->route('match');

		return $this->user()->can('leave', $this->match);

	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return arrays
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
		$this->match->removePlayer($this->user());
		event(new PlayerLeft($this->match, $this->user()));
	}


	/**
	 * @param Validator $validator
	 */
	public function withValidator(Validator $validator): void {

		$validator->after(function ($validator) {
			if ($this->match->ended()) {
				$validator->errors()->add('ended', __('match/requests.ended'));
			}
		});
	}
}
