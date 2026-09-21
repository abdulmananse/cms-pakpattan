<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactCategoryRequest extends FormRequest
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
        $id = @$this->contact_category->id;

        return [
            'name' => 'required|string|max:255|unique:contact_categories,name,' . $id,
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|in:0,1',
        ];
    }

    /**
     * Prepare data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => (int) $this->is_active,
            ]);
        } else {
            // Default active on create if not provided, or 0 if form submitted without checkbox
            if ($this->isMethod('post')) {
                $this->merge(['is_active' => 1]);
            }
        }
    }
}
