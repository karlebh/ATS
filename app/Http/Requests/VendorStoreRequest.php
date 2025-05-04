<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorStoreRequest extends FormRequest
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
            'code'  => ['required', 'string', 'max:255', 'unique:vendors'],
            'name'  => ['required', 'string', 'unique:vendors', 'max:255'],
            'phone' => ['required', 'string', 'unique:vendors'],
            'email' => ['required', 'email', 'unique:vendors', 'max:255'],
        ];
    }
}
