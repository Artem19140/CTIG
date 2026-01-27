<?php

namespace App\Http\Requests\ExamBlock;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExamBlockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            //
        ];
    }
}
