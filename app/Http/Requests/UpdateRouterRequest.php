<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class UpdateRouterRequest extends FormRequest
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
            'department' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!DB::table('departments')->whereRaw('LOWER(name) = ?', [strtolower($value)])->exists()) {
                        $fail('The selected department is invalid.');
                    }
                },
            ],

            'instruction' => ['required', 'string'],

            'materials' => ['sometimes', 'array'],
            'materials.*.id' => ['nullable', 'integer'],
            'materials.*.description' => ['required_with:materials', 'string'],
            'materials.*.quantity' => ['required_with:materials', 'integer'],
            'materials.*.price' => ['required_with:materials', 'numeric'],
            'materials.*.vendor_email' => ['required_with:materials', 'email'],
            'materials.*.invoice_id' => ['required_with:materials', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            // General fields
            'department_id.required' => 'The department ID is required.',
            'department_id.string' => 'The department ID must be a string.',
            'instruction.required' => 'The instruction is required.',
            'instruction.string' => 'The instruction must be a string.',

            // Materials array
            'materials.array' => 'The materials must be provided as an array.',

            // Materials.* fields
            'materials.*.description.required_with' => 'The description is required for each material.',
            'materials.*.description.string' => 'The description must be a string.',

            'materials.*.quantity.required_with' => 'The quantity is required for each material.',
            'materials.*.quantity.integer' => 'The quantity must be an integer.',

            'materials.*.price.required_with' => 'The price is required for each material.',
            'materials.*.price.numeric' => 'The price must be a number.',

            'materials.*.vendor_email.required_with' => 'The vendor email is required for each material.',
            'materials.*.vendor_email.email' => 'The vendor email must be a valid email address.',

            'materials.*.invoice_id.required_with' => 'The invoice ID is required for each material.',
            'materials.*.invoice_id.integer' => 'The invoice ID must be an integer.',

            'materials.*.position.required_with' => 'The position is required for each material.',
            'materials.*.position.integer' => 'The position must be an integer.',
        ];
    }
}
