<?php

namespace App\Http\Requests\Revenue;

use Illuminate\Foundation\Http\FormRequest;

class ReopenRevenueComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        $complaint = $this->route('revenue_complaint') ?? $this->route('complaint');
        return $complaint && $complaint->canBeReopenedBy($this->user());
    }

    public function rules(): array
    {
        return [
            'reopening_reason' => ['required', 'string'],
        ];
    }
}
