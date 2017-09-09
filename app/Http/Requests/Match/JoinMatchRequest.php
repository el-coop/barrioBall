<?php

namespace App\Http\Requests\Match;

use App\Events\Match\JoinRequest;
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
    	if(! $this->user() || $this->user()->inMatch($this->route('match')) || $this->user()->sentRequest($this->route('match'))){
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
            'message' => 'string|max:500|nullable'
        ];
    }

	public function commit(){
    	$match = $this->route('match');
    	if($this->user()->isManager($match)){
			$match->addPlayer($this->user());
			$message = __('match/show.joined');
			event(new UserJoined($this->user(),$match));
		} else {
			$match->joinRequests()->save($this->user());
			$message = __('match/show.joinMatchSent');
			event(new JoinRequest($this->user(),$match, $this->input('message')));
		}
		return $message;
	}
}
