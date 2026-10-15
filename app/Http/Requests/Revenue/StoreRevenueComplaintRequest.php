<?php

namespace App\Http\Requests\Revenue;

use Illuminate\Foundation\Http\FormRequest;

class StoreRevenueComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('Land Revenue Matters Create') 
            || $this->user()->can('Revenue Matters Create') 
            || $this->user()->hasRole('Super Admin');
    }

    public function rules(): array
    {
        return [
            'citizen_name' => ['required', 'string', 'max:255'],
            'father_husband_name' => ['nullable', 'string', 'max:255'],
            'cnic' => ['nullable', 'string', 'max:20'],
            'contact_number' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'tehsil' => ['nullable', 'string', 'max:100'],
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'attachments.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:10240'],
        ];
    }
}
