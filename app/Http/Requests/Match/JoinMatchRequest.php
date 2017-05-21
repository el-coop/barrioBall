<?php

namespace App\Http\Requests\Match;

use App\Events\Match\UserJoined;
use Illuminate\Foundation\Http\FormRequest;

class JoinMatchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
    	if(! $this->user() || $this->user()->inMatch($this->route('match'))){
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

	public function commit(){
		$message = '';
    	$match = $this->route('match');
    	if($this->user()->isManager($match)){
			$match->addPlayer($this->user());
			$message = "You joined the match";
			event(new UserJoined($this->user(),$this->route('match')));
		} else {
			$message = "Your request was sent. Wait for a reply from match manager";
    		//TODO - send user join request
		}

		return $message;
	}
}
