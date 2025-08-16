<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ComplaintRequest extends FormRequest
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
        $rules = [
            'category' => 'required|exists:categories,id',
            'description' => 'required|string|max:500',
            'location' => 'required|string|max:100',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];

        if (!Auth::check()) {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['username'] = ['required', 'string', 'max:255', 'unique:'.User::class];
            $rules['mobile'] = ['required'];
            $rules['email'] = ['nullable', 'string', 'lowercase', 'email', 'max:255'];
            $rules['address'] = ['nullable', 'string', 'max:500'];
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'username' => str_replace('-', '', $this->get('username')),
            'mobile' => str_replace('-', '', $this->get('mobile'))
        ]);
    }
}
