<?php

namespace App\Http\Requests\Match;

use App\Events\Match\PlayerJoined;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AcceptJoinRequest extends FormRequest {
	protected $match;
	protected $user;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool {
		$this->match = $this->route('match');
		if ($this->user() && $this->user()->isManager($this->match)) {
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
			'user' => 'required|numeric|exists:users,id',
			'message' => 'string|max:500|nullable',
		];
	}

	/**
	 * @param Validator $validator
	 */
	public function withValidator(Validator $validator): void {
		$this->user = User::find($this->input('user'));
		$validator->after(function ($validator) {
			if (!$this->match->hasJoinRequest($this->user)) {
				$validator->errors()->add('request', __('match/requests.requestNotExistent'));
			}
			if ($this->match->isFull()) {
				$validator->errors()->add('full', __('match/requests.isFull'));
			}
			if ($this->match->ended()) {
				$validator->errors()->add('ended', __('match/requests.ended'));
			}
		});
	}

	/**
	 *
	 */
	public function commit(): void {
		$this->match->addPlayer($this->user);
		$this->match->joinRequests()->detach($this->user);
		event(new PlayerJoined($this->match, $this->user, $this->input('message')));
	}
}
