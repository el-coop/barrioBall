<?php

namespace App\Http\Requests\User;

use App\Events\User\MessageSent;
use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !! $this->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'message' => 'required'
        ];
    }

    public function commit(){
        $conversation = $this->route('conversation');
        event(new MessageSent($this->user(), $this->input('message'), $conversation));
    }
}
