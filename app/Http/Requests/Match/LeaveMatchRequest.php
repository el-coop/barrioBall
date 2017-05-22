<?php

namespace App\Http\Requests\Match;

use App\Events\Match\UserLeft;
use Illuminate\Foundation\Http\FormRequest;

class LeaveMatchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		if(! $this->user() || ! $this->user()->inMatch($this->route('match'))){
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
            //
        ];
    }

	public function commit() {
		$this->route('match')->removePlayer($this->user());
		event(new UserLeft($this->user(),$this->route('match')));
		return true;
	}
}
