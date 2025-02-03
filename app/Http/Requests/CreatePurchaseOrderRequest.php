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
            'client_name' => ['required', 'string'],
            'client_email' => ['required', 'email'],
            'client_company_name' => ['required', 'string'],
            'budget' => ['required', 'numeric', 'min:0', 'max:999999999999999999999.99'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:now'],
        ];
    }
}
