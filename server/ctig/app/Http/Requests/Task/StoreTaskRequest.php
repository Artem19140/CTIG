<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subblockId'=>[
                'required',
                'integer',
                'min:1',
                'exists:blocks,id'
            ],
            'order'=> [
                'required',
                'int',
                'min:1'
            ],
            'type' => [
                'required',
                'string'
            ]
        ];
    }
}
