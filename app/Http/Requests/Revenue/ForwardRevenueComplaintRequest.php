<?php

namespace App\Http\Requests\Revenue;

use Illuminate\Foundation\Http\FormRequest;

class ForwardRevenueComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        $complaint = $this->route('revenue_complaint') ?? $this->route('complaint');
        return $complaint && $complaint->canBeForwardedBy($this->user());
    }

    public function rules(): array
    {
        return [
            'recipient_id' => ['required', 'integer', 'exists:users,id'],
            'instructions' => ['required', 'string'],
            'attachments.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:10240'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $complaint = $this->route('revenue_complaint') ?? $this->route('complaint');
            if ($complaint && (int) $this->recipient_id === (int) $complaint->current_holder_id) {
                $validator->errors()->add('recipient_id', 'Cannot forward the complaint to yourself.');
            }
        });
    }
}
