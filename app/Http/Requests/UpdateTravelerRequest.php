<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UpdateTravelerRequest extends FormRequest
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
            'shop_name' => ['required', 'string', 'max:255'],
            'shop_email' => ['required', 'email', 'max:255'],
            'start_at' => ['required', 'date'],
            'due_at' => ['required', 'date', 'after_or_equal:start_at'],
            'completed_at' => ['nullable', 'date', 'after_or_equal:start_at'],

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

            'items' => ['required', 'array'],
            'items.*.id' => ['nullable', 'integer', 'exists:inspection_traveler_items,id'],
            'items.*.part_number' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    preg_match("/items\.(\d+)\.part_number/", $attribute, $matches);
                    $index = $matches[1] ?? null;

                    $partId = $index !== null && isset($this->items[$index]['id']) ? $this->items[$index]['id'] : null;

                    if ($partId) {
                        $uniqueRule = Rule::unique('inspection_traveler_items', 'part_number')->ignore($partId);
                    } else {
                        $uniqueRule = Rule::unique('inspection_traveler_items', 'part_number');
                    }

                    if (!validator(['part_number' => $value], ['part_number' => $uniqueRule])->passes()) {
                        $fail('The part_number has already been taken.');
                    }
                }
            ],
            'items.*.quantity' => ['required', 'integer'],
            'items.*.description' => ['required', 'string'],
            'items.*.department' => ['required', 'string'],
            'items.*.finish' => ['required', 'string'],
            'items.*.rev' => ['required', 'integer'],
            'items.*.ht_stress' => ['required', 'string'],
            'items.*.ship_out' => ['required', 'date'],
            'items.*.shipped' => ['required', 'date'],
            'items.*.deburr' => ['required', 'string'],
            'items.*.tooling_check' => ['required', 'string'],
            'items.*.process_review' => ['required', 'string'],
            'items.*.fai_completed' => ['required', 'string'],

            'operations' => ['required', 'array'],
            'operations.*.id' => ['nullable', 'integer'],
            'operations.*.outside_ops' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    preg_match("/operations\.(\d+)\.outside_ops/", $attribute, $matches);
                    $index = $matches[1] ?? null;

                    $operationId = $index !== null && isset($this->operations[$index]['id']) ? $this->operations[$index]['id'] : null;

                    if ($operationId) {
                        $uniqueRule = Rule::unique('inspection_traveler_operations', 'outside_ops')->ignore($operationId);
                    } else {
                        $uniqueRule = Rule::unique('inspection_traveler_operations', 'outside_ops');
                    }

                    if (!validator(['outside_ops' => $value], ['outside_ops' => $uniqueRule])->passes()) {
                        $fail('The outside_ops has already been taken.');
                    }
                }
            ],
            'operations.*.vendor' => ['required', 'integer'],
            'operations.*.out_by' => ['required', 'date', 'after_or_equal:start_at'],
            'operations.*.back_by' => ['required', 'date', 'after_or_equal:start_at,operations.*.out_by'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'The items field is required.',
            'items.array' => 'The items field must be an array.',

            'items.*.part_number.required' => 'Part number is required at position :position.',
            'items.*.part_number.integer' => 'Part number must be an integer at position :position.',

            'items.*.quantity.required' => 'Quantity is required at position :position.',
            'items.*.quantity.integer' => 'Quantity must be an integer at position :position.',

            'items.*.description.required' => 'Description is required at position :position.',
            'items.*.description.string' => 'Description must be a string at position :position.',

            'items.*.department.required' => 'Department is required at position :position.',
            'items.*.department.string' => 'Department must be a string at position :position.',

            'items.*.finish.required' => 'Finish is required at position :position.',
            'items.*.finish.string' => 'Finish must be a string at position :position.',

            'items.*.rev.required' => 'Revision is required at position :position.',
            'items.*.rev.integer' => 'Revision must be an integer at position :position.',

            'items.*.ht_stress.required' => 'Heat stress is required at position :position.',
            'items.*.ht_stress.string' => 'Heat stress must be a string at position :position.',

            'items.*.ship_out.required' => 'Ship out date is required at position :position.',
            'items.*.ship_out.date' => 'Ship out date must be a valid date at position :position.',

            'items.*.shipped.required' => 'Shipped date is required at position :position.',
            'items.*.shipped.date' => 'Shipped date must be a valid date at position :position.',

            'items.*.deburr.required' => 'Deburr status is required at position :position.',
            'items.*.deburr.string' => 'Deburr status must be a string at position :position.',

            'items.*.tooling_check.required' => 'Tooling check is required at position :position.',
            'items.*.tooling_check.string' => 'Tooling check must be a string at position :position.',

            'items.*.process_review.required' => 'Process review is required at position :position.',
            'items.*.process_review.string' => 'Process review must be a string at position :position.',

            'items.*.fai_completed.required' => 'FAI completion status is required at position :position.',
            'items.*.fai_completed.string' => 'FAI completion status must be a string at position :position.',

            'operations.required' => 'The operations field is required.',
            'operations.array' => 'The operations field must be an array.',

            'operations.*.outside_ops.required' => 'Outside operations field is required at position :position.',
            'operations.*.outside_ops.string' => 'Outside operations must be a string at position :position.',

            'operations.*.vendor.required' => 'Vendor is required at position :position.',
            'operations.*.vendor.string' => 'Vendor must be a string at position :position.',

            'operations.*.out_by.required' => 'Out by date is required at position :position.',
            'operations.*.out_by.date' => 'Out by date must be a valid date at position :position.',

            'operations.*.back_by.required' => 'Back by date is required at position :position.',
            'operations.*.back_by.date' => 'Back by date must be a valid date at position :position.',

        ];
    }
}
