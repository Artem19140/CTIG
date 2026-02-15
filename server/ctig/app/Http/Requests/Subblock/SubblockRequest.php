<?php

namespace App\Http\Requests\Subblock;

use Illuminate\Foundation\Http\FormRequest;

class SubblockRequest extends FormRequest
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
            'name' => [
                'required',
                'string'
            ],
            'minMark' => [
                'required',
                'integer',
                'min:1'
            ],
            'examBlockId' => [
                'required',
                'integer',
                'min:1'
            ],
            'order' => [
                'required',
                'integer',
                'min:1'
            ],
        ];
    }
}
