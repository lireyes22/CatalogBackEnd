<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MongoApiModel;
use App\Models\Supplier;
use App\Models\Client;
use MongoDB\BSON\ObjectId;

class User extends MongoApiModel
{
    use HasFactory;
    protected $collection = 'Users';
    protected $fillable = [
        'User_Uid',
        'User_Name',
        'User_Email',
        'User_Password',
        'User_PhoneNumber',
        'User_Status',
        'User_RegistrationDate',
        'User_Roles',
    ];

    public function isSupplier()
    {
        return Supplier::where('User', new ObjectId($this->_id))->first();
    }
    public function isClient()
    {
        return Client::where('User', new ObjectId($this->_id))->first();
    }
}
