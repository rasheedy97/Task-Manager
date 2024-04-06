<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            "title" => "string|max:50",
            "description" => "string|max:255",
            "due_date" => "date",
            "assignee_id" => "numeric|exists:users,id",
            "status_id" => "numeric|exists:statuses,id",
            "dependant_id" => "numeric|exists:tasks,id"
        ];
    }
}
