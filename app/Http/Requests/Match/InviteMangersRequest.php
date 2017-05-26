<?php

namespace App\Http\Requests\Match;

use Illuminate\Foundation\Http\FormRequest;

class InviteMangersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
	{
		if(! $this->user()->isManager($this->route('match'))){
			return false;
		}
		return true;
	}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		return [
			'invite_managers' => 'required|array',
			'invite_managers.*' => 'integer'
        ];
    }

	public function commit() {
		// TODO - handle management invitation requests
	}
}
