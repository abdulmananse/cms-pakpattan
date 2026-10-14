<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'name' => 'required|max:50',
            'designation' => 'nullable|max:50',
            'roles' => 'required|array|min:1',
            'roles.*' => 'required|exists:roles,id',
            'username' => 'required|max:100|unique:users,username,'.$this->id,
            'email' => 'nullable|email|max:100',
            'mobile' => 'nullable',
            'department_ids' => 'nullable',
            'permission_ids' => 'nullable',
            'source_id' => 'nullable|exists:sources,id',
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()]
        ];
    }

    public function messages()
    {
        return [
            'roles.required' => 'Please select at least one role.',
            'roles.min' => 'Please select at least one role.',
            'roles.*.exists' => 'The selected role is invalid.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'username' => str_replace('-', '', $this->get('username'))
        ]);
    }
}
