<?php

namespace App\Http\Requests\Admin;

use App\Events\Admin\AdminAdded;
use App\Models\Admin;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AddAdminRequest extends FormRequest {
	protected $user;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool {
		return $this->user()->can('admin', Admin::class);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array {
		return [
			//
		];
	}

	/**
	 * @param Validator $validator
	 */
	public function withValidator(Validator $validator): void {
		$this->user = $this->route('user');
		$validator->after(function ($validator) {
			if ($this->user->isAdmin()) {
				$validator->errors()->add('alreadyAdmin', __('global.error'));
			}
		});
	}

	/**
	 *
	 */
	public function commit(): void {
		$this->user->makeAdmin();
		event(new AdminAdded);
	}
}
