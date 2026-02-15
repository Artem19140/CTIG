<?php

namespace App\Http\Resources\Address;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'address' => $this->address,
            'creator' => (new UserResource($this->whenLoaded('creator'))),
            'id' => $this->id
        ];
    }
}
