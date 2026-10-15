<?php

namespace App\Http\Requests\Revenue;

use Illuminate\Foundation\Http\FormRequest;

class SendBackRevenueComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        $complaint = $this->route('revenue_complaint') ?? $this->route('complaint');
        return $complaint && $complaint->canBeSentBackBy($this->user());
    }

    public function rules(): array
    {
        return [
            'report' => ['required', 'string'],
            'attachments.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:10240'],
        ];
    }
}
