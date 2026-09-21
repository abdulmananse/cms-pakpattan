<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfficerContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'officer_name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'office_establishment' => 'nullable|string|max:255',
            'primary_mobile' => 'required|string|max:30',
            'alternate_phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'contact_category_id' => 'required|exists:contact_categories,id',
            'lifecycle_status' => 'required|in:Active,Inactive',
            'is_pcm' => 'nullable|boolean',
            'is_favorite' => 'nullable|boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    /**
     * Prepare data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_pcm' => $this->boolean('is_pcm'),
            'is_favorite' => $this->boolean('is_favorite'),
        ]);
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'officer_name' => 'officer name',
            'department_id' => 'department',
            'contact_category_id' => 'category group',
            'primary_mobile' => 'primary mobile number',
            'alternate_phone' => 'alternate phone number',
            'lifecycle_status' => 'lifecycle status',
            'is_pcm' => 'price control magistrate',
            'is_favorite' => 'favorite',
        ];
    }
}
