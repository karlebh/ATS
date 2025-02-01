<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_id' => ['nullable', 'exists:users,id'],
            'job_id' => ['nullable', 'exists:jobs,id'],
            'budget' => ['nullable', 'numeric', 'min:0', 'max:9999999999999.99'],
            'progress' => [
                'nullable',
                'string',
                // Rule::in(['not_started', 'in_progress', 'completed'])
            ],
            'status' => [
                'nullable',
                'string',
                // Rule::in(['pending', 'approved', 'rejected'])
            ],
            'current_team' => ['nullable', 'string', 'max:255'],
            'timeline' => ['nullable', 'integer'],
        ];
    }
}
