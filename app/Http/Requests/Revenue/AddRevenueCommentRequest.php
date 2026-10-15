<?php

namespace App\Http\Requests\Revenue;

use Illuminate\Foundation\Http\FormRequest;

class AddRevenueCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $complaint = $this->route('revenue_complaint') ?? $this->route('complaint');
        return $complaint && $complaint->canBeCommentedBy($this->user());
    }

    public function rules(): array
    {
        return [
            'comment' => ['required', 'string'],
            'attachments.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:10240'],
        ];
    }
}
