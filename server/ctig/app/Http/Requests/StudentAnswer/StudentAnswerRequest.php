<?php

namespace App\Http\Requests\StudentAnswer;

use Illuminate\Foundation\Http\FormRequest;

class StudentAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'studentAnswer' => ['nullable']
        ];
    }
}
