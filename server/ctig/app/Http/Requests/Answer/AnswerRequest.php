<?php

namespace App\Http\Requests\Answer;

use Illuminate\Foundation\Http\FormRequest;

class AnswerRequest extends FormRequest
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
            "contain" => [
                'required',
                'string'
            ],
            "isCorrect" =>[
                'required',
                'boolean'
            ],
            "taskVariantId" =>[
                'required',
                'integer',
                'min:1'
            ],
            "mark" =>[
                'required',
                'integer',
                'min:1'
            ],
        ];
    }
}
