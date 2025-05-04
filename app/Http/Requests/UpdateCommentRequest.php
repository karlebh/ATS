<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class UpdateCommentRequest extends FormRequest
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
            'content' => ['required', 'min:3'],
            'upload_id' => [
                $hasUploadedFiles ? 'required' : 'nullable',
                function ($attribute, $value, $fail) {
                    $query = DB::table('temporary_files')
                        ->where('user_id', auth()->id());

                    if (! is_null($value) && $query->where('upload_id', $value)->doesntExist()) {
                        $fail('Invalid upload id');
                    }
                },
            ],
        ];
    }
}
