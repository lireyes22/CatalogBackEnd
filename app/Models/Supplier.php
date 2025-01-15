<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MongoApiModel;

class Supplier extends MongoApiModel
{
    use HasFactory;
    protected $collection = 'Suppliers';
    protected $fillable = [
        'Supplier_Status',
        'Supplier_CollectionMethod',
        'Supplier_Address',
        'User',
    ];
}
