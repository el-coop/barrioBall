<?php

namespace App\Http\Requests\Match;

use App\Events\Match\MatchDeleted;
use Illuminate\Foundation\Http\FormRequest;

class DeleteMatchRequest extends FormRequest
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
            //
        ];
    }

	public function commit() {
		$match = $this->route('match');
		$match->delete();
		event(new MatchDeleted($this->user(),$match));

		return true;
	}
}
