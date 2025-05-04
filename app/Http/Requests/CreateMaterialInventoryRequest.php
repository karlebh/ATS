<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMaterialInventoryRequest extends FormRequest
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
            'code' => ['required', 'string', 'min:6', 'max:50', 'unique:material_inventories,code'],
            'title' => ['required', 'string', 'min:2', 'max:255', 'unique:material_inventories,title'],
            'quantity' => ['required', 'integer', 'min:1'],
            'description' => ['required', 'string', 'min:2'],
        ];
    }
}
