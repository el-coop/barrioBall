<?php

namespace App\Http\Requests\Match;

use App\Events\Match\ManagerLeft;
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
		if ($this->user() && $this->user()->isManager($this->match) && $this->match->managers()->count() > 1) {
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
}
