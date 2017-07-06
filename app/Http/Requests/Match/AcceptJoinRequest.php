<?php

namespace App\Http\Requests\Match;

use App\Events\Match\UserJoined;
use App\Models\Match;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class AcceptJoinRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(){
    	$match = $this->route('match');
    	if($this->user()->isAdmin($match)){
			return true;
		}
		return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
			'user' => 'required|numeric|exists:users,id',
			'message' => 'string|max:500|nullable'
        ];
    }

	public function commit() {
		$match = $this->route('match');
		$user = User::find($this->input('user'));
		if(! $match->hasJoinRequest($user)){
			return false;
		}

		$match->addPlayer($user);
		$match->joinRequests()->detach($user);
		event(new UserJoined($user,$match,$this->input('message')));
		return "{$user->username} successfully added to the match";
	}
}
