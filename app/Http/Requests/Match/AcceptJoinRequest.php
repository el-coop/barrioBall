<?php

namespace App\Http\Requests\Match;

use App\Events\Match\UserJoined;
use App\Models\Match;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class AcceptJoinRequest extends FormRequest {
	protected $match;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool {
		$this->match = $this->route('match');
		if ($this->user() && $this->user()->isAdmin($this->match)) {
			return true;
		}

		return false;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'user' => 'required|numeric|exists:users,id',
			'message' => 'string|max:500|nullable',
		];
	}

	public function withValidator($validator) {
		$user = User::find($this->input('user'));
		$validator->after(function ($validator) use ($user) {
			if (!$this->match->hasJoinRequest($user)) {
				$validator->errors()->add('request', __('match/requests.requestNotExistent'));
			}
		});
	}

	public function commit() {
		$user = User::find($this->input('user'));
		$this->match->addPlayer($user);
		$this->match->joinRequests()->detach($user);
		event(new UserJoined($user, $this->match, $this->input('message')));
	}
}
