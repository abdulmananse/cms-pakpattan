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
            'location' => 'nullable|string|max:100',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'source' => 'nullable',
        ];

        if ($this->source == 'Online Form') {
            $rules['location'] = 'required|string|max:100';
        } else {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['email'] = ['nullable', 'string', 'lowercase', 'email', 'max:255'];
            $rules['address'] = ['nullable', 'string', 'max:500'];
            $rules['username'] = ['nullable', 'string', 'max:255'];
            $rules['mobile'] = ['nullable'];
        }

        if (!Auth::check()) {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['email'] = ['nullable', 'string', 'lowercase', 'email', 'max:255'];
            $rules['address'] = ['nullable', 'string', 'max:500'];
            $rules['username'] = ['required', 'string', 'max:255', 'unique:'.User::class];
            $rules['mobile'] = ['required'];
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        $data = [];
 
        if($this->filled('username')) {
            $data['username'] = str_replace('-', '', $this->get('username'));
        }
        if($this->filled('mobile')) {
            $data['mobile'] = str_replace('-', '', $this->get('mobile'));
        }
        
        $this->merge($data);
    }
}
