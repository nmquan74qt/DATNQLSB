<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FieldResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->fieldType ? $this->fieldType->name : 'N/A',
            'price_per_hour' => $this->price,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'links' => [
                'self' => url('/api/fields/' . $this->id),
                'book' => url('/fields/' . $this->id)
            ]
        ];
    }
}
