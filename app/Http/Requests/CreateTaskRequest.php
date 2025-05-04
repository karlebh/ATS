<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTaskRequest extends FormRequest
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
            'assignee_id' => ['nullable', 'exists:users,id'],
            'purchase_order_id' => ['required', 'exists:purchase_orders,id'],
            'name' => ['required', 'string', 'max:255'],
            'details' => ['required', 'string'],
        ];
    }
}
