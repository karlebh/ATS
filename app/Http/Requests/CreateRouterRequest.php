<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class CreateRouterRequest extends FormRequest
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
            'purchase_order_id' => ['required', 'integer', 'exists:purchase_orders,id'],
            'department' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!DB::table('departments')->whereRaw('LOWER(name) = ?', [strtolower($value)])->exists()) {
                    $fail('The selected department is invalid.');
                }
            },],
            'instruction' => ['required', 'string'],

            'materials' => ['required', 'array'],
            'materials.*.description' => ['required', 'string'],
            'materials.*.quantity' => ['required', 'integer'],
            'materials.*.price' => ['required', 'numeric'],
            'materials.*.vendor_email' => ['required', 'email'],
            'materials.*.invoice_id' => ['required', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            // Department and Instruction
            'department_id.required' => 'The department ID field is required.',
            'department_id.string' => 'The department ID must be a valid string.',
            'instruction.required' => 'The instruction field is required.',
            'instruction.string' => 'The instruction must be a valid string.',

            // Materials Array
            'materials.required' => 'The materials field is required.',
            'materials.array' => 'The materials must be provided as an array.',

            // Materials Fields
            'materials.*.description.required' => 'The material description in position :position is required.',
            'materials.*.description.string' => 'The material description in position :position must be a valid string.',
            'materials.*.quantity.required' => 'The material quantity in position :position is required.',
            'materials.*.quantity.integer' => 'The material quantity in position :position must be an integer.',
            'materials.*.price.required' => 'The material price in position :position is required.',
            'materials.*.price.numeric' => 'The material price in position :position must be a valid number.',
            'materials.*.vendor_email.required' => 'The vendor email in position :position is required.',
            'materials.*.vendor_email.email' => 'The vendor email in position :position must be a valid email address.',
            'materials.*.invoice_id.required' => 'The invoice ID in position :position is required.',
            'materials.*.invoice_id.integer' => 'The invoice ID in position :position must be an integer.',
        ];
    }
}
