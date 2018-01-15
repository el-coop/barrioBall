<?php

namespace App\Http\Requests\Match;

use App\Events\Match\ManagersInvited;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class InviteMangersRequest extends FormRequest {

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
			'invite_managers' => 'required|array',
			'invite_managers.*' => 'integer',
		];
	}

	/**
	 *
	 */
	public function commit(): void {
		$invited = User::findMany($this->input('invite_managers'));
		$invited = $invited->reject->isManager($this->match);
		$invited = $invited->reject->hasManageInvite($this->match);
		$invited->each->manageInvite($this->match);
		event(new ManagersInvited($this->match,$this->user(),$invited));
	}
}
