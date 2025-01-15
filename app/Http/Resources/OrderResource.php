<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use MongoDB\BSON\ObjectId;

use App\Models\Order;
use App\Models\Supplier;
use App\Models\Client;

use App\Http\Resources\ProductResource;
use App\Http\Resources\SupplierResource;
use App\Http\Resources\ClientResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $format = $request->query('format');

        if ($format == 1) {
            $data = parent::toArray($request);
            $order = Order::where('_id', new ObjectId($data['_id']))->first();

            // Get the products from the order
            $products = $order->getProducts();
            // Get the client ID from the data
            $client = $order->getClient();
            // Get the supplier ID from the first product
            $supplier = $products->first()->getSupplier();

            $resource = [
                'Order_ID' => $data['_id'],
                ...$data,
                'Products' => ProductResource::collection($products),
                'relationship' => [
                    'Supplier' => SupplierResource::make($supplier),
                    'Client' => ClientResource::make($client)
                ]
            ];

            unset($resource['_id']);
            unset($resource['Client']);

            return $resource;
        }
        $data = parent::toArray($request);
        $resource = [
            'Order_ID' => $data['_id'],
            ...$data,
            'relationships' => [
                'Client' => [
                    'Client_ID' => (string) $data['Client']['Client_ID'],
                    'Client_Name' => $data['Client']['Client_Name'],
                ],
                'Products' => array_map(function ($product) {
                    $product['Product_ID'] = (string) $product['Product_ID'];
                    return $product;
                }, $data['Products'])
            ],
        ];
        unset($resource['Client']);
        unset($resource['Products']);
        unset($resource['_id']);
        return $resource;
    }
}
