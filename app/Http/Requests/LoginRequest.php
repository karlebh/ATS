<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;

class LoginRequest extends FormRequest
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
            'data' => ['required', 'string', function ($attribute, $value, $fail) {
                $exists = DB::table('users')
                    ->whereRaw('LOWER(email) = ?', [strtolower($value)])
                    ->orWhereRaw('LOWER(username) = ?', [strtolower($value)])
                    ->exists();

                if (! $exists) {
                    $fail('The email or username does not match any user.');
                }
            },],
            'password' => [
                'required',
            ],
        ];
    }

    public function messages()
    {
        return [
            'data.required' => 'The email or username is required.',
            'data.string' => 'The email or username must be a valid email or username.',
        ];
    }
}
