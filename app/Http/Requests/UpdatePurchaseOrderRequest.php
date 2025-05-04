<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
        $hasUploadedFiles = DB::table('temporary_files')
            ->where('user_id', auth()->id())
            ->exists();

        return [
            'router_id' => ['nullable', 'exists:routers,id'],
            'client_name' => ['nullable', 'string'],
            'client_email' => ['nullable', 'email'],
            'client_company_name' => ['nullable', 'string'],
            'budget' => ['nullable', 'numeric', 'min:0', 'max:999999999999999999999.99'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:now'],

            'upload_id' => [
                $hasUploadedFiles ? 'required' : 'nullable',
                function ($attribute, $value, $fail) {
                    $query = DB::table('temporary_files')
                        ->where('user_id', auth()->id());

                    if (!is_null($value) && $query->where('upload_id', $value)->doesntExist()) {
                        $fail('Invalid upload id');
                    }
                },
            ],

            'parts' => ['sometimes', 'array'],
            'parts.*.id' => ['nullable', 'integer'],
            'parts.*.number' => [
                'required_with:parts',
                'integer',
                function ($attribute, $value, $fail) {
                    preg_match("/parts\.(\d+)\.number/", $attribute, $matches);
                    $index = $matches[1] ?? null;

                    $partId = $index !== null && isset($this->parts[$index]['id']) ? $this->parts[$index]['id'] : null;

                    if ($partId) {
                        $uniqueRule = Rule::unique('parts', 'number')->ignore($partId);
                        if (!validator(['number' => $value], ['number' => $uniqueRule])->passes()) {
                            $fail('The number has already been taken.');
                        }
                    } else {
                        $uniqueRule = Rule::unique('parts', 'number');
                        if (!validator(['number' => $value], ['number' => $uniqueRule])->passes()) {
                            $fail('The number has already been taken.');
                        }
                    }
                }
            ],
            'parts.*.name' => ['required_with:parts', 'string'],
            'parts.*.quantity' => ['required_with:parts', 'integer'],
            'parts.*.price' => ['required_with:parts', 'numeric'],
            'parts.*.finish' => ['required_with:parts', 'string'],
            'parts.*.rev' => ['required_with:parts', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            // Client Information
            'client_name.string' => 'The client name must be a valid string.',
            'client_email.email' => 'The client email must be a valid email address.',
            'client_company_name.string' => 'The client company name must be a valid string.',

            // Budget
            'budget.numeric' => 'The budget must be a valid number.',
            'budget.min' => 'The budget must be at least 0.',
            'budget.max' => 'The budget must not exceed 999,999,999,999,999,999,999.99.',

            // Dates
            'start_date.date' => 'The start date must be a valid date.',
            'end_date.date' => 'The end date must be a valid date.',
            'end_date.after_or_equal' => 'The end date must be equal to or after the current date.',

            // Parts Array
            'parts.array' => 'The parts must be provided as an array.',

            // Parts Fields
            'parts.*.number.required_with' => 'The part number in position :position is required when parts are provided.',
            'parts.*.number.integer' => 'The part number in position :position must be an integer.',
            'parts.*.name.required_with' => 'The part name in position :position is required when parts are provided.',
            'parts.*.name.string' => 'The part name in position :position must be a valid string.',
            'parts.*.quantity.required_with' => 'The part quantity in position :position is required when parts are provided.',
            'parts.*.quantity.integer' => 'The part quantity in position :position must be an integer.',
            'parts.*.price.required_with' => 'The part price in position :position is required when parts are provided.',
            'parts.*.price.numeric' => 'The part price in position :position must be a valid number.',
            'parts.*.finish.required_with' => 'The part finish in position :position is required when parts are provided.',
            'parts.*.finish.string' => 'The part finish in position :position must be a valid string.',
            'parts.*.rev.required_with' => 'The part revision in position :position is required when parts are provided.',
            'parts.*.rev.integer' => 'The part revision in position :position must be an integer.',
        ];
    }
}
