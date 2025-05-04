<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorUpdateRequest extends FormRequest
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
            'code'  => ['nullable', 'string', 'max:255', 'unique:vendors'],
            'name'  => ['nullable', 'string', 'unique:vendors', 'max:255'],
            'phone' => ['nullable', 'string', 'unique:vendors'],
            'email' => ['nullable', 'email', 'unique:vendors', 'max:255'],
        ];
    }
}
