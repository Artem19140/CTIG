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
            'contain' => 'required|string',
            'examBlockId'=>'required|integer|min:0|exists:exam_blocks,id',//вынести отсюда!
            'fipiGuid'=>'required|string'
        ];
    }
}
