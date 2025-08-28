<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules;
use App\Http\Requests\BaseRequest;

class StoreUserRequest extends BaseRequest
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
            'role' => 'required|exists:roles,id',
            'department_ids' => 'nullable',
            'source_id' => 'nullable|exists:sources,id',
            'name' => 'required|max:50',
            'designation' => 'nullable|max:50',
            'username' => 'required|max:50|unique:users,username',
            'email' => 'nullable|email|max:100',
            'mobile' => 'nullable',
            'password' => ['required', 'confirmed', Rules\Password::defaults()]
            
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'username' => str_replace('-', '', $this->get('username'))
        ]);
    }
}
