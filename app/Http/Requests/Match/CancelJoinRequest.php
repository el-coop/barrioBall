<?php

namespace App\Http\Requests\Match;

use App\Events\Match\JoinRequestCenceled;
use Illuminate\Foundation\Http\FormRequest;

class CancelJoinRequest extends FormRequest {
	protected $match;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool {
		$this->match = $this->route('match');

		return $this->user()->can('cancelRequest', $this->match);
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
	public function commit(): string {
		$this->match->cancelJoinRequest($this->user());
		$message = __('match/show.cancelMessage');
		event(new JoinRequestCenceled($this->match, $this->user()));

		return $message;
	}
}
