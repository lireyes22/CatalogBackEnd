<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\BSON\ObjectId;

use App\Traits\ApiTrait;


class Order extends MongoApiModel
{
    use HasFactory, ApiTrait;
    
    protected $collection = 'Orders';
    protected $fillable = [
        'Order_GroupID',
        'Order_Date',
        'Order_Status',
        'Order_Total',
        'Order_PaymentMethod',
        'Order_Products',
        'Order_ShippingMethod',
        'Product',
        'Client',
        'Order_ShippingAddress'
    ];

    protected $allowFilter = [
        'Order_Date',
        'Order_Status'
    ];

    protected $allowFilterId = [
        'Client.Client_ID',
    ];

    protected $allowSort = [
        'Order_Total'
    ];

    

    public function getProducts($products = null)
    {
        $nProducts = 0;
        if($products == null) {
            // Get the product IDs from the data
            $productIds = array_map(fn($productData) => new ObjectId($productData['Product_ID']), $this['Products']);    
            $nProducts = count($productIds);
        } else {
            $productIds = array_map(fn($productData) => new ObjectId($productData), $products);
            $nProducts = count($productIds);
        }

        // Get the products from the database
        $getProducts = Product::whereIn('_id', $productIds)->get();
        if($nProducts != count($getProducts)) {
            abort(404, 'Product not found');
        }
        return $getProducts;
    }

    public function getClient($client = null)
    {

        if($client != null) {
            // Get the client ID from the data
            return Client::where('_id', new ObjectId($client))->first();
        } else {
            return Client::where('_id', new ObjectId($this['Client']['Client_ID']))->first();
        }
    }

    public function getTotalPrice($products, $metodPayment)
    {
        $products = $this->getProducts($products);
        $totalPrice = 0;

        $existPaymentMethod = false;

        foreach ($products as $product) {
            foreach ($product['Product_PaymentMethodsAvailable'] as $PaymentMethod) {
                if ($PaymentMethod['Product_PaymentMethod'] == $metodPayment) {
                    $totalPrice += $PaymentMethod['Product_Price'];
                    $existPaymentMethod = true;
                }
            }
            if (!$existPaymentMethod) {
                abort(404, 'Payment method not found');
            }
        }
        return $totalPrice;
    }

    public function getShippingProducts($products, $metodPayment)
    {
        $products = $this->getProducts($products);
        $shippingProducts = [];

        foreach ($products as $product) {
            $shippingProducts[] = $product->getShippingFormat($metodPayment);
        }
        return $shippingProducts;
    }

    public function getShippingClient($client)
    {
        $client = $this->getClient($client);
        if($client == null) {
            abort(404, 'Client not found');
        }
        return $client->getShippingFormat();
    }
}
