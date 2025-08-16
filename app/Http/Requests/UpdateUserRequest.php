<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'role' => 'nullable|exists:roles,id',
            'username' => 'required|max:100|unique:users,username,'.$this->id,
            'email' => 'nullable|email|max:100',
            'mobile' => 'nullable',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'username' => str_replace('-', '', $this->get('username'))
        ]);
    }
}
