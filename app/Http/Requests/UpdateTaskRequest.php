<?php

namespace App\Http\Requests;

use App\Constants\TaskProgress;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
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
            'status' => ['nullable', Rule::in([
                TaskProgress::COMPLETED,
                TaskProgress::IN_PROGRESS,
                TaskProgress::IN_QUEUE,
                TaskProgress::SECONDARY_OPS,
            ])],
            'name' => ['nullable', 'string', 'max:255'],
            'details' => ['nullable', 'string'],
            'assignee_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
