<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;

class FrdoReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'success' => $this->boolean('success')
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'success' =>['nullable', 'boolean'],
            'examDate' => ['required', 'date']
        ];
    }
}
