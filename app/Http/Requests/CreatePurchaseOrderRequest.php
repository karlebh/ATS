<?php

namespace App\Http\Requests;

use App\Rules\PurchaseOrderValidateUploadId;
use App\Rules\ValidateUploadId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
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
        $hasUploadedFiles = DB::table('temporary_files')
            ->where('user_id', auth()->id())
            ->exists();

        return [
            'client_name' => ['required', 'string'],
            'client_email' => ['required', 'email'],
            'client_company_name' => ['required', 'string'],
            'budget' => ['required', 'numeric', 'min:0', 'max:999999999999999999999.99'],
            'start_date' => ['required', 'date', 'after_or_equal:now'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
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

            'parts' => ['required', 'array'],
            'parts.*.number' => ['required', 'integer', 'unique:parts,number'],
            'parts.*.name' => ['required', 'string'],
            'parts.*.quantity' => ['required', 'integer'],
            'parts.*.price' => ['required', 'numeric'],
            'parts.*.finish' => ['required', 'string'],
            'parts.*.rev' => ['required', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            // Client Information
            'client_name.required' => 'The client name field is required.',
            'client_name.string' => 'The client name must be a valid string.',
            'client_email.required' => 'The client email field is required.',
            'client_email.email' => 'The client email must be a valid email address.',
            'client_company_name.required' => 'The client company name field is required.',
            'client_company_name.string' => 'The client company name must be a valid string.',

            'upload_id.required' => 'The upload_id is needed since you already uploaded an image. Use the one generated during upload. Alternatively, you can delete the previous upload to create purchase order without files',

            // Budget
            'budget.required' => 'The budget field is required.',
            'budget.numeric' => 'The budget must be a valid number.',
            'budget.min' => 'The budget must be at least 0.',
            'budget.max' => 'The budget must not exceed 999,999,999,999,999,999,999.99.',

            // Dates
            'start_date.required' => 'The start date field is required.',
            'start_date.date' => 'The start date must be a valid date.',
            'start_date.after_or_equal' => 'The start date must be equal to or after the current date.',
            'end_date.required' => 'The end date field is required.',
            'end_date.date' => 'The end date must be a valid date.',
            'end_date.after_or_equal' => 'The end date must be equal to or after the start date.',

            // Parts Array
            'parts.required' => 'The parts field is required.',
            'parts.array' => 'The parts must be provided as an array.',

            // Parts Fields
            'parts.*.number.required' => 'The part number in position :position is required.',
            'parts.*.number.integer' => 'The part number in position :position must be an integer.',
            'parts.*.number.unique' => 'The part number in position :position must be unique.',
            'parts.*.name.required' => 'The part name in position :position is required.',
            'parts.*.name.string' => 'The part name in position :position must be a valid string.',
            'parts.*.quantity.required' => 'The part quantity in position :position is required.',
            'parts.*.quantity.integer' => 'The part quantity in position :position must be an integer.',
            'parts.*.price.required' => 'The part price in position :position is required.',
            'parts.*.price.numeric' => 'The part price in position :position must be a valid number.',
            'parts.*.finish.required' => 'The part finish in position :position is required.',
            'parts.*.finish.string' => 'The part finish in position :position must be a valid string.',
            'parts.*.rev.required' => 'The part revision in position :position is required.',
            'parts.*.rev.integer' => 'The part revision in position :position must be an integer.',
        ];
    }
}
