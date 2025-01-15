<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        $resource = [
            'Product_ID' => $data['_id'],
            ...$data,
            'Product_Slug' => strtolower(str_replace(' ', '-', $data['Product_Name'])),
        ];
        unset($resource['_id']);

        return $resource;
    }
}
