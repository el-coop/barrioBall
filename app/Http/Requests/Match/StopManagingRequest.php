<?php

namespace App\Http\Requests\Match;

use App\Events\Match\ManagerLeft;
use Illuminate\Foundation\Http\FormRequest;

class StopManagingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

		$match = $this->route('match');
		if(! $this->user()->isManager($match) || $match->managers()->count() < 2){
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
		$this->route('match')->removeManager($this->user());
		event(new ManagerLeft($this->user(),$this->route('match')));
		return true;
	}
}
