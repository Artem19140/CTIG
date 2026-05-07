<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserPostRequest extends FormRequest
{
    public function authorize(Request $request): bool
    {
        return $request->user()->isOrgAdmin() || $request->user()->isSuperAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'roles' => ['required', 'array'],
            'roles.*'=>['required', 'integer', 'min:1','exists:roles,id'],
            'surname'=>['required','string'],
            'name'=>['required','string'],
            'patronymic'=>['required','string'],
            'email'=>['required','email', 'unique:users,email'],
            'password'=>['required', 'min:8',  'confirmed'],
            'jobTitle' => ['required', 'string']
        ];
    }
}
