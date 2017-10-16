<?php

namespace App\Http\Requests\Match;

use App\Events\Match\PlayerLeft;
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
		if ($this->user() && $this->user()->inMatch($this->match)) {
			return true;
		}

		return false;
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
}
