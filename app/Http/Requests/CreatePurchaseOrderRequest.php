<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class CreatePurchaseOrderRequest extends FormRequest
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
            'client_id' => ['required', 'exists:users,id'],
            'job_id' => ['required', 'exists:jobs,id'],
            'budget' => ['required', 'numeric', 'min:0', 'max:9999999999999.99'],
            'progress' => [
                'required',
                'string',
                // Rule::in(['not_started', 'in_progress', 'completed'])
            ],
            'status' => [
                'required',
                'string',
                // Rule::in(['pending', 'approved', 'rejected'])
            ],
            'current_team' => ['required', 'string', 'max:255'],
            'timeline' => ['required', 'integer'],
        ];
    }
}
