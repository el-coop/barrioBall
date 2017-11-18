<?php

namespace App\Http\Requests\Match;

use App\Events\Match\MatchDeleted;
use Illuminate\Foundation\Http\FormRequest;

class DeleteMatchRequest extends FormRequest {
	protected $match;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool {
		$this->match = $this->route('match');

		return $this->user()->can('delete',$this->match);

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
		$this->match->load('managers','registeredPlayers');
		$this->match->delete();
		event(new MatchDeleted($this->match, $this->user()));
	}
}
