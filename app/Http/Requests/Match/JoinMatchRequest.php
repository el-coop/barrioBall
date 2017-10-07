<?php

namespace App\Http\Requests\Match;

use App\Events\Match\JoinRequestSent;
use App\Events\Match\UserJoined;
use Illuminate\Foundation\Http\FormRequest;

class JoinMatchRequest extends FormRequest {
	protected $match;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		$this->match = $this->route('match');

		if (!$this->user() || $this->user()->inMatch($this->match) || $this->user()->sentRequest($this->match) || $this->match->isFull()) {
			return false;
		}

		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'message' => 'string|max:500|nullable',
		];
	}

	public function commit() {
		if ($this->user()->isManager($this->match)) {
			$this->match->addPlayer($this->user());
			$message = __('match/show.joined');
			event(new UserJoined($this->user(), $this->match));
		} else {
			$this->match->joinRequests()->save($this->user());
			$message = __('match/show.joinMatchSent');
			event(new JoinRequestSent($this->user(), $this->match, $this->input('message')));
		}

		return $message;
	}
}
