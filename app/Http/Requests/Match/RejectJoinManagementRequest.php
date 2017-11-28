<?php

namespace App\Http\Requests\Match;

use App\Events\Match\ManageInvitationRejected;
use Illuminate\Foundation\Http\FormRequest;

class RejectJoinManagementRequest extends FormRequest {
	protected $match;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool {
		$this->match = $this->route('match');

		return $this->user()->can('joinManagement', $this->match);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			//
		];
	}

	public function commit(): void {
		$this->match->removeManageInvitation($this->user());
		event(new ManageInvitationRejected($this->match, $this->user()));
	}
}
