<?php

namespace App\Http\Requests\Report;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MinistryEducationReportRequest extends FormRequest
{
    public function authorize(): bool{
        return true;
    }

    protected function prepareForValidation(){
        $this->merge([
            'lastWeek' => $this->boolean('lastWeek')
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'lastWeek' => ['required', 'bool'],
            'dateFrom' => ['required_if_declined:lastWeek', 'nullable', 'date'],
            'dateTo' => ['required_if_declined:lastWeek', 'nullable', 'date'],
        ];
    }
}
