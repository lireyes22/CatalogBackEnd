<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MongoApiModel;
use MongoDB\BSON\ObjectId;

use App\Traits\ApiTrait;

class Product extends MongoApiModel
{
    use HasFactory, ApiTrait;
    protected $collection = 'Products';
    protected $fillable = [
        'Product_Category',
        'Product_Name',
        'Product_Tags',
        'Product_MainImage',
        'Product_Images',
        'Product_Description',
        'Product_Availability',
        'Product_Stock',
        'Product_Ratings',
        'Product_PromoCodes',
        'Product_ShippingMethods',
        'Product_PaymentMethodsAvailable',
        'Product_SupplierID',
    ];

    protected $allowFilter = [
        'Product_Category',
        'Product_Name',
        'Product_Availability',
        'Product_Stock',
    ];

    public function getSupplier()
    {
        return Supplier::where('_id', new ObjectId($this['Product_SupplierID']))->first();
    }

    public function getShippingFormat($metodPayment){

        $productPrice = 0;

        foreach ($this['Product_PaymentMethodsAvailable'] as $ShippingMethod) {
            if ($ShippingMethod['Product_PaymentMethod'] == $metodPayment) {
                $productPrice = $ShippingMethod['Product_Price'];
            }
        }
        
        return [
            'Product_ID' => new ObjectId($this['_id']),
            'Product_Name' => $this['Product_Name'],
            'Product_Price' => $productPrice
        ];
    }

}
