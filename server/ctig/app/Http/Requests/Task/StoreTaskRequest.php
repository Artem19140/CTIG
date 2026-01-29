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
            'exam_block_id'=>'required|integer|min:0|exists:exam_blocks,id',
            'fipi_guid'=>'required|string'
        ];
    }

    public function attributes(){
        return [
            'contain'=> 'содержимое вопроса',
            'exam_block_id' => 'блок экзамена',
            'fipi_guid'=>'идентификатора фипи'
        ];
    }
}
