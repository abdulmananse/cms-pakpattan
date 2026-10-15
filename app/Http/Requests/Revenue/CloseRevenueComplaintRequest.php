<?php

namespace App\Http\Requests\Revenue;

use Illuminate\Foundation\Http\FormRequest;

class CloseRevenueComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        $complaint = $this->route('revenue_complaint') ?? $this->route('complaint');
        return $complaint && $complaint->canBeClosedBy($this->user());
    }

    public function rules(): array
    {
        return [
            'closing_comments' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:10240'],
        ];
    }
}
