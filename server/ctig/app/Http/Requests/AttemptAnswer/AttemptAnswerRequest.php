<?php

namespace App\Http\Requests\AttemptAnswer;

use Illuminate\Foundation\Http\FormRequest;

class AttemptAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'attemptAnswer' => ['nullable'],
            'taskVariantId' => ['required', 'integer', 'min:1']
        ];
    }
}
