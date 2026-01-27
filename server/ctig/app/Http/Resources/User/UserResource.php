<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->resource->id,
            'surname' => $this->resource->surname,
            'patronymic' => $this->resource->patronymic,
            'name'=>$this->resource->name,
            'email'=>$this->resource->email, 
            'token'=>$this->resource->token
        ];
    }
}
