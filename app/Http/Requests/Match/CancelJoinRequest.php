<?php

namespace App\Http\Requests\Match;

use Illuminate\Foundation\Http\FormRequest;

class CancelJoinRequest extends FormRequest
{
    protected $match;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->match = $this->route('match');

        return $this->user()->sentRequest($this->match);
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

    /**
     *
     */
    public function commit(): string {
        $this->match->cancelJoinRequest($this->user());
        $message = __('match/show.cancelMessage');
        return $message;
    }
}
