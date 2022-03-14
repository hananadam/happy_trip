<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'number' => $this->number,
            'expire_month' => $this->expire_month,
            'expire_year' => $this->expire_year,
            'cvv' => $this->cvv,
            'address' => $this->address,
            'confirmed' => $this->confirmed,
        ];
    }
}
