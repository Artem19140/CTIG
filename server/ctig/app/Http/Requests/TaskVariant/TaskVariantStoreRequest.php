<?php

namespace App\Http\Requests\TaskVariant;

use Illuminate\Foundation\Http\FormRequest;

class TaskVariantStoreRequest extends FormRequest
{
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
            'contain' => [
                'required',
                'string'
            ],
            'taskId' => [
                'required',
                'int',
                'min:1'
            ],
            'groupId' => [
                'required',
                'int',
                'min:1'
            ],
            'fipiGuid' => [
                'required',
                'string'
            ],
            'mark' => [
                'required',
                'int',
                'min:0'
            ]
        ];
    }
}
